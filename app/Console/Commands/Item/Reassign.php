<?php

namespace App\Console\Commands\Item;

use App\Modules\Customer\Models\Customer;
use App\Modules\Customer\Repositories\CustomerRepository;
use App\Modules\Item\Models\Item;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class Reassign extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'item:reassign
                            {--from= : From User Paddle Number.}
                            {--to= : To User Paddle Number.}';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reassign Seller';

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
        $from = $this->option("from");
        $to = $this->option("to");

        $currentCustomer = Customer::where('ref_no',$from)->first();
        $newCustomer = Customer::where('ref_no',$to)->first();

        $items = Item::where('customer_id', $currentCustomer->id)->get();

        $this->info("Total Item to reassign : " . $items->count());

        $continue = $this->anticipate('Continue ?', ['y','n'], 'n');

        if($continue == 'n') return 0;

        foreach ($items as $key => $item){

            $count = $key+1;

            $item->update(
                [
                    'customer_id' => $newCustomer->id,
                    'item_code' => $count,
                    'item_number' => "$to/$count",
                ]
            );
            $this->info("Reassigned : " . $item->name);
        }

        return 0;
    }
}
