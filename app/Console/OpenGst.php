<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Modules\Customer\Models\Customer;

class OpenGst extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'open:gst';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Customer Open Gst';

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
        $this->info(date('Y-m-d H:i:s').' ======= Start - Customer Open Gst  =======');
        \Log::info('======= Start - Customer Open Gst =======');

        try{
            $customers = Customer::all();
            foreach ($customers as $key => $customer) {
                if($customer->country_of_residence == 702){
                    $customer->buyer_gst_registered = 1;
                    $customer->save();
                }
            }

        } catch (\Exception $e) {
            $this->error("ERROR - Customer Open Gst - " . $e->getMessage());
            \Log::error("ERROR - Customer Open Gst - " . $e->getMessage());
        }

        \Log::info(date('Y-m-d H:i:s').' ======= End - Customer Open Gst =======');
        $this->info('======= End - Customer Open Gst =======');
    }
}
