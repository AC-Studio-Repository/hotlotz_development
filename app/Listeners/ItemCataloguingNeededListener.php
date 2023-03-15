<?php

namespace App\Listeners;

use App\Exceptions\QueueFailReport;
use App\Events\ItemCataloguingNeededEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Modules\Item\Models\Item;
use App\Modules\Item\Http\Repositories\ItemRepository;
use App\Modules\Category\Models\CategoryProperty;
use App\Helpers\NHelpers;
use DB;

class ItemCataloguingNeededListener implements ShouldQueue
{
    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 100;
    /**
     * Create the event listener.
     *
     * @return void
     */

    protected $itemRepository;
    public function __construct(ItemRepository $itemRepository)
    {
        $this->itemRepository = $itemRepository;
    }

    /**
     * Handle the event.
     *
     * @param  ItemCataloguingNeededEvent  $event
     * @return void
     */
    public function handle(ItemCataloguingNeededEvent $event)
    {
        \Log::info('Start - ItemCataloguingNeededEvent');

        try {
            $item = $event->item;

            $itemproperties = $item->category_data;
            // dd($itemproperties);
            \Log::info('Item Properties => '.print_r($itemproperties, true));

            $categoryproperties = CategoryProperty::where('category_id', $item->category_id)->select('id', 'key', 'value', 'field_type', 'is_required', 'is_filter')->get();

            $status = Item::_COMPLETED_;
            foreach ($categoryproperties as $index => $property) {
                if ($property->key != 'Sub Category') {
                    if (($itemproperties[$property->key] == null || $itemproperties[$property->key] == '') && $property->is_required == 'Required') {
                        $status = null;
                    }
                }
            }

            \Log::info('Cataloguing Needed is '.$status);

            // Item::where('id',$item->id)->update(['cataloguing_needed'=>$status] + NHelpers::updated_at_by());
            $payload = ['cataloguing_needed'=>$status];
            $result = $this->itemRepository->update($item->id, $payload, true, 'Cataloguing Needed');
        } catch (\Exception $e) {
            \Log::error("Caught Exception ('{$e->getMessage()}')\n{$e}\n");
            throw new QueueFailReport($e);
        }

        \Log::info('End - ItemCataloguingNeededEvent');
    }
}
