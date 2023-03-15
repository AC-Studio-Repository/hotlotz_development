<?php

namespace App\Console\Commands;

use App\Modules\Customer\Models\Customer;
use App\Modules\Customer\Repositories\CustomerRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Date;
use Konekt\Address\Models\Country;

class MigrateDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hotlotz:migrate
                            {mode=selected : Migrate mode. Support "selected, bulk"}
                            {--from=0 : From User ID. Set "0" to disable. Inclusive}
                            {--to=0 : To User ID. Set "0" to disable. Inclusive}
                            {--id=0 : User IDs to migrate(e.g: 1,2,3)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate Customer from Old backend to New Backend';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    protected $customerRepository;

    public function __construct(CustomerRepository $customerRepository)
    {
        parent::__construct();
        $this->customerRepository = $customerRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $old = DB::connection("old");
        $new = DB::connection("mysql");

        $mode = $this->getArgument("mode");

        if($mode == "selected"){
            $id = $this->getOption("id");
            $query = "select * from ppb_users where id IN ($id) order by id";
        }else if($mode == "bulk"){
            $from = $this->getOption("from");
            $to = $this->getOption("to");
            $query = "select * from ppb_users where id >= $from AND id <= $to order by id";
        }

        $users = $old->select($query);

        foreach ($users as $user) {
            try {
                $payload = $this->generatePayload($old, $new, $user);

                $customer = Customer::where("ref_no", $user->client_ref)->get();

                if (count($customer) == 0) {
                    $this->customerRepository->create($payload);
                    $this->info($payload['fullname'] . " migrated.");
                } else {
                    $this->customerRepository->update($customer[0]->id, $payload);
                    $this->info($payload['fullname'] . " updated.");
                }
            } catch (\Exception $e) {
                $this->error("Migration failed for Id : " . $user->id);
                $this->error("Caught Exception ('{$e->getMessage()}')\n{$e}\n");
                return 2;
            }
        }

        return 0;
    }

    private function getOption($key)
    {
        $value = $this->option($key);
        return $value;
    }

    private function getArgument($arg)
    {
        $value = $this->argument($arg);

        return $value;
    }

    protected function generatePayload($old, $new, $request)
    {
        $payload['title'] = $request->salutation;
        $payload['salutation'] = $request->salutation;
        $payload['firstname'] = $request->first_name;
        $payload['lastname'] = $request->last_name;
        $payload['fullname'] = $request->first_name.' '.$request->last_name;
        $payload['email'] = $request->email;
        $payload['password'] = "password";
        $payload['hash_password'] = Hash::make("password");
        $payload['phone'] = $request->phone;
        $payload['country_id'] = $request->country;
        $payload['dialling_code'] = $request->primary_country_code;
        $payload['ref_no'] = $request->client_ref;

        if ($request->company_name != '') {
            $payload['type'] = 'organization';
            $payload['company_name'] = $request->company_name;
        } else {
            $payload['type'] = 'individual';
        }

        $payload['sg_uen_number'] = null;
        $payload['seller_gst_registered'] = 0;
        $payload['gst_number'] = null;
        $payload['marketing_auction'] = 1;
        $payload['marketing_marketplace'] = 1;
        $payload['marketing_chk_events'] = 1;
        $payload['marketing_chk_congsignment_valuation'] = 1;
        $payload['marketing_hotlotz_quarterly'] = 1;

        $payload['buyer_gst_registered'] = 0;
        $payload['reg_gst_sg'] = 0;

        $payload['buyer_premium_override'] = "none";
        $payload['buyers_premium'] = $request->buyers_min_premium;

        $payload['country_id'] = 702;
        $payload['country_of_residence'] = 702;

        $old_location = $old->select("select name from ppb_locations where id=$request->country");
        if (count($old_location) != 0) {
            $new_location = $new->select("select id from countries where full_name like '%" . $old_location[0]->name ."%'");
            if (count($new_location) != 0) {
                $payload['country_id'] = $new_location[0]->id;
                $payload['country_of_residence'] = $new_location[0]->id;
            }
        }

        $payload['created_by'] = 2;

        return $payload;
    }
}
