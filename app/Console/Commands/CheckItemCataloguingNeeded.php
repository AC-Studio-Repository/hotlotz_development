<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Modules\Item\Models\Item;
use App\Modules\Category\Models\CategoryProperty;
use App\Events\ItemCataloguingNeededEvent;

class CheckItemCataloguingNeeded extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'item:check_item_cataloguing_needed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Item Cataloguing Needed Command';

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
        $this->info(date('Y-m-d H:i:s').' ======= Start - Daily CheckItemCataloguingNeeded =======');
        \Log::info('======= Start - Daily CheckItemCataloguingNeeded =======');

        try{            
            $items = Item::whereNull('cataloguing_needed')->whereNotNull('category_data')->get();
            // dd($items);

            \Log::info('Item count '.count($items));

            if(count($items)>0){
                foreach($items as $item){
                    event(new ItemCataloguingNeededEvent($item));
                }
            }

        } catch (\Exception $e) {
            $this->error("ERROR - CheckItemCataloguingNeeded - " . $e);
            \Log::error("ERROR - CheckItemCataloguingNeeded - " . $e);
        }

        $this->info(date('Y-m-d H:i:s').' ======= End - Daily CheckItemCataloguingNeeded =======');
        \Log::info('======= End - Daily CheckItemCataloguingNeeded =======');
    }
}
