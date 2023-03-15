<?php

namespace App\Console\Commands\Item;

use App\Modules\Customer\Models\Customer;
use App\Modules\Item\Http\Repositories\ItemRepository;
use App\Modules\Item\Models\Item;
use Illuminate\Console\Command;

class Approve extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'item:approve
                            {--paddle= User Paddle Number (Ref. No) to Approve: }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Approve item under specified user.';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    protected $itemRepository;

    public function __construct(ItemRepository $itemRepository)
    {
        parent::__construct();
        $this->itemRepository = $itemRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $userId = $this->option("paddle");

        $customer = Customer::where('ref_no',$userId)->first();

        $items = Item::where('customer_id', $customer->id)->get();

        $this->info("Total Item to Approve : " . $items->count());

        $continue = $this->anticipate('Continue ?', ['y','n'], 'n');

        if($continue == 'n') return 0;

        foreach ($items as $item){
            try{
                $payload = [
                    'cataloguing_approver_id'=> 1,
                    'is_cataloguing_approved'=>'Y',
                    'cataloguing_approval_date' => date('Y-m-d H:i:s'),
                    'cataloguing_needed' => 'N',
                ];
                $result = $this->itemRepository->update($item->id, $payload, true, 'ApprovedCataloguing');
                $this->info("Approved ItemID - " . $item->id);
            }catch (\Exception $e){
                $this->error("Fail to approve ItemId - " . $item->id ."  with error " . $e->getMessage());
                return 2;
            }
        }

        return 0;
    }
}
