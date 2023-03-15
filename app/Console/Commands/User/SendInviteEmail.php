<?php

namespace App\Console\Commands\User;

use App\Modules\Customer\Models\Customer;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class SendInviteEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:sendInvite
                            {mode=selected : Migrate mode. Support "selected, bulk"}
                            {--from=0 : From User ID. Set "0" to disable. Inclusive}
                            {--to=0 : To User ID. Set "0" to disable. Inclusive}
                            {--id=0 : User Paddle number to migrate(e.g: A1,A2,A3)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Bulk Invite Email to User';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $old = DB::connection("old");

        $mode = $this->argument("mode");

        if($mode == "selected"){
            $id = $this->option("id");
            $query = "select * from ppb_users where client_ref IN (\"$id\") order by id";
        }else if($mode == "bulk"){
            $from = $this->option("from");
            $to = $this->option("to");
            $query = "select * from ppb_users where id >= $from AND id <= $to order by id";
        }

        $users = $old->select($query);

        $firstConsigner = collect();

        foreach ($users as $user) {
            try {

                if($firstConsigner->contains($user->client_ref)){
                    $this->info("Client - " . $user->client_ref . " is First Consignor.");
                }else{
                    $customer = Customer::where("ref_no", $user->client_ref)->first();
                    $customer->email_verified_at = null;
                    $customer->has_agreement = 0;
                    $customer->save();

                    $this->info("Client - " . $user->client_ref . " sending...");

                    Mail::to($customer->email)
                        ->send(new \App\Mail\User\Welcome($customer));

                    $this->info("Client - " . $user->client_ref . " send.");
                }
            }catch (\Exception $e){
                $this->error($e->getMessage());
                $this->info("Client - " . $user->client_ref . " fail.");
            }
        }
    }
}
