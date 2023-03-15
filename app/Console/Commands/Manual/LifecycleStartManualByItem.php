<?php

namespace App\Console\Commands\Manual;

use Illuminate\Console\Command;
use App\Modules\Item\Models\Item;
use App\Jobs\LifecycleStart;

class LifecycleStartManualByItem extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lifecycle:start_by_item {item_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Item Lifecycle starts by Item ID';

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
        $this->info(date('Y-m-d H:i:s').' ======= Start - LifecycleStartManualByItem =======');
        \Log::channel('lifecycleLog')->info('======= Start - LifecycleStartManualByItem =======');

        try{
            $item_id = $this->argument('item_id');
            \Log::channel('lifecycleLog')->info('item_id : '.$item_id);

            $item = Item::find($item_id);
            \Log::channel('lifecycleLog')->info('Item exist? '.isset($item));

            if(isset($item) && $item->is_cataloguing_approved === 'Y' && $item->permission_to_sell === 'Y' && $item->status === Item::_PENDING_){

                \Log::channel('lifecycleLog')->info('LifecycleStartManualByItem - dispatch LifecycleStart Job '.$item_id);
                LifecycleStart::dispatch($item_id);
            }

        } catch (\Exception $e) {
            $this->error("ERROR - LifecycleStartManualByItem - " . $e->getMessage());
            \Log::channel('lifecycleLog')->error("ERROR - LifecycleStartManualByItem - " . $e->getMessage());
        }

        $this->info(date('Y-m-d H:i:s').' ======= End - LifecycleStartManualByItem =======');
        \Log::channel('lifecycleLog')->info('======= End - LifecycleStartManualByItem =======');
    }
}
