<?php

namespace App\Modules\ItemDuplicator\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Item\Http\Repositories\ItemRepository;
use App\Modules\Item\Models\Item;
use App\Modules\Item\Models\ItemImage;
use DB;
use Illuminate\Http\Request;


class ItemDuplicatorController extends Controller
{
    protected $itemRepository;
    public function __construct(ItemRepository $itemRepository){
        $this->itemRepository = $itemRepository;
    }

    /**
     * Displays the category index
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $item = app(Item::class);
        $item_lists = Item::whereNull('items.deleted_at')
            ->orderby('name')
            ->pluck('name','id')
            ->all();

        $lifecycle = [
            'same' => 'Same',
            'random' => 'Random',
        ];
        $category = [
            'same' => 'Same',
            'random' => 'Random',
        ];
        $subCategory = [
            'same' => 'Same',
            'random' => 'Random',
        ];

        $seller = [];

        $data = [
            'item' => $item,
            'item_lists' => $item_lists,
            'life_cycle' => $lifecycle,
            'category' => $category,
            'sub_category' => $subCategory
        ];

        return view('item_duplicator::index', $data);
    }

    public function duplicate(Request  $request){

        $itemId = $request->item_id;

        for($i=1;$i<=$request->total_copy;$i++){
            $this->duplicateItem($itemId);
        }

        return redirect( route('item_duplicator.item_duplicator.index') );
    }

    public function duplicateItem($item_id)
    {
        DB::beginTransaction();
        try {
            $item = Item::find($item_id);
            $payload = $this->itemRepository->getPayloadForDuplicateItem($item);

            $new_item = $this->itemRepository->create($payload);
            $this->itemRepository->cloneImage($item_id, $new_item->id);
            $this->itemRepository->cloneLifecycle($item_id, $new_item->id);
            $this->itemRepository->cloneFeeStructure($item_id, $new_item->id);

            DB::commit();

            flash()->success(__('Duplicated item :name has been created', ['name' => $new_item->name]));

        } catch (\Exception $e) {
            DB::rollback();
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
        }
    }
}
