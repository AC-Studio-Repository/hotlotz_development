<?php

namespace App\Console\Commands\Item;

use Illuminate\Console\Command;
use App\Modules\Item\Models\ItemImage;
use App\Events\Item\CreateThumbnailEvent;

class SyncThumbnail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'item:sync-thumbnail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync thumbnail for items';

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
        $this->info(date('Y-m-d H:i:s').' ======= Start - Sync thumbnail for items Command =======');
        \Log::info('======= Start - Sync thumbnail for items Command =======');

        $itemImages = ItemImage::where('thumbnail_path', null)->whereNotNull('item_id')->take(100)->get();
       
        foreach ($itemImages as $itemImage) {
            if ($itemImage->item_id) {
                event(new CreateThumbnailEvent($itemImage->item_id));
            }
        }

        $this->info(date('Y-m-d H:i:s').' ======= End - Sync thumbnail for items Command =======');
        \Log::info('======= End - Sync thumbnail for items Command =======');
    }
}
