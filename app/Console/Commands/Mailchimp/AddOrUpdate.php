<?php

namespace App\Console\Commands\Mailchimp;

use Illuminate\Console\Command;
use App\Modules\Customer\Models\Customer;

class AddOrUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mailchimp:sync
    {--id=all : Access customers table id. (e.g, --id=1,2,3)}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync to mailchimp';

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
        $this->info(date('Y-m-d H:i:s').' ======= Start - Mailchimp Sync Command =======');
        \Log::channel('mailchimpLog')->info('======= Start - Mailchimp Sync Command =======');

        $id = $this->option('id');

        if($id == 'all'){
            $customers = Customer::where('mailchimp_sync', 0)->get();
        }else{
            $ids = [];
            foreach (explode(",", $id) as $eachId) {
                $ids[] = $eachId;
            }
            $customers = Customer::whereIn('id', $ids)->where('mailchimp_sync', 0)->get();
        }

        foreach($customers as $customer){
            if($customer->hasVerifiedEmail()){
                $this->info($customer->email);
                event( new \App\Events\Mailchimp\AddOrUpdateEvent($customer->id));
            }
        }

        $this->info(date('Y-m-d H:i:s').' ======= End - Mailchimp Sync Command =======');
        \Log::channel('mailchimpLog')->info('======= End - Mailchimp Sync Command =======');
    }
}
