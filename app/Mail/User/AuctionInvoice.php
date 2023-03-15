<?php

namespace App\Mail\User;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AuctionInvoice extends Mailable
{
    use Queueable, SerializesModels;

    #18
    public $customerInvoice;
    /**
     *
     * @return void
     */
    public function __construct($customerInvoice)
    {
        $this->customerInvoice = $customerInvoice;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $custoemrInvoice = $this->customerInvoice;
        $auction = $custoemrInvoice->auction;
        $items = [];
        foreach ($custoemrInvoice->items as $customerInvoiceItem) {
            if ($customerInvoiceItem->item) {
                $auction_item = DB::table('auction_items')->where('item_id', $customerInvoiceItem->item->id)->where('auction_id', $auction->id)->first();
                $items[] = [
                    'lot_number' => $auction_item->lot_number,
                    'item_name' => $customerInvoiceItem->item->name
                ];
            }
        }
        $array_column  = array_column($items, 'lot_number');
        array_multisort($array_column, SORT_ASC, $items);

        $data = [
            'subject' => "Your auction invoice",
            'items' => $items,
            'auction_name' => $auction->title,
            'link' => url( config('app.url').route('my-receipt', 'awaiting', [], false) ),
        ];

        return $this->view('emails.user.auction_invoice', $data);
    }
}