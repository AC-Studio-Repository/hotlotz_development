<?php

namespace App\Jobs\Item;

use Exception;
use Illuminate\Bus\Queueable;
use App\Modules\Item\Models\Item;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CheckoutTimeOut implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $payload;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 0;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($payload)
    {
        $this->payload = $payload;
    }

    public function tags()
    {
        return ['render', 'checkout item time-out: ' . $this->payload['customer_id']];
    }
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() {
        \Log::channel('checkoutItem')->info('======= Start - Checkout Item Time-out Job '. $this->payload['customer_id']  .'=======');
        \Log::channel('checkoutItem')->info('======= Payload - Checkout Item Time-out Job '. print_r($this->payload, true) .'=======');

        try {
            $payload = $this->payload;
            $items = $payload['items'];
            foreach ($items as $item) {
                $item_id = $item['id'];
                $pluckItemStatus = Item::find($item_id, ['status']);
                if($pluckItemStatus !== null && $pluckItemStatus->status == 'Sold'){
                    DB::table('items')->where('id', $item_id)->update(
                        [
                            'buyer_id'      => null,
                            'status'             => Item::_IN_MARKETPLACE_,
                            'sold_price' => null,
                            'sold_date' => null,
                            'storage_date' => null,
                            'tag' => null,
                        ]
                    );

                    DB::table('item_lifecycles')->where('item_id', $item_id)->update(
                        [
                            'buyer_id'      => null,
                            'status'             => null,
                            'sold_price' => null,
                            'sold_date' => null,
                        ]
                    );
                }
            }
            \Log::channel('checkoutItem')->info('======= End - Checkout Item Time-out Job '. $this->payload['customer_id'] .'=======');
        } catch (\Exception $e) {
            \Log::channel('checkoutItem')->error("Caught Exception ('{$e->getMessage()}')\n{$e}\n");

            throw $e;
        }
    }

    /**
     * The job failed to process.
     *
     * @param  Exception $exception
     * @return void
     */
    public function failed(Exception $exception)
    {
        app(\App\Exceptions\QueueFailReport::class)->report($exception);

        \Log::channel('xeroLog')->error('======= Failed - Checkout Item Time-out Job '. print_r($this->payload, true) .'=======');
    }
}
