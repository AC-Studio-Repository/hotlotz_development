<?php

namespace App\Console\Commands\xero;

use Illuminate\Console\Command;
use App\Modules\Item\Models\Item;
use App\Modules\Auction\Models\Auction;
use App\Modules\Xero\Models\XeroInvoice;
use App\Modules\Xero\Repositories\XeroInvoiceRepository;
use App\Modules\Xero\Repositories\XeroInvoiceModelRepository;

class GenereateInvoice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:invoice
        {--ID|id=all : Access xero_invoices table id. (e.g, -ID [id ?? all] or --id [id ?? all])}
        {--A|auction=all : Access multi auction ids separate by comma to create invoice. (e.g, -A [ids ?? all] or --auction [ids ?? all])}
        {--C|customer=all : Access multi customer ids separate by comma to create invoice. (e.g, -C [ids ?? all] or --customer [ids ?? all])}
        {--T|type=all : Access type (bill & invoice) to create invoice; default all. (e.g, -T [all ?? bill ?? invoice] or --type [all ?? bill ?? invoice])}
        {--F|for=auction : Access type (auction & marketplace) to create invoice; default auction. (e.g, -F [auction ?? marketplace] or --for [auction ?? marketplace])}
        {--I|item=all : Access multi item ids separate by comma to create invoice (e.g, -I [ids ?? all] or --item [ids ?? all])}
        {--S|status=0 : Access xero_invoices table status( 0 incomplete, 1 complete, 2 invoice complete, 3 bill complete ). (e.g, -S [0 ?? 1 ?? 2 ?? 3] or --status [0 ?? 1 ?? 2 ?? 3])}
        ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Genereate xero invoice';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    protected $xeroInvoiceModelRepository;

    protected $xeroInvoiceRepository;

    public function __construct(
        XeroInvoiceModelRepository $xeroInvoiceModelRepository,
        XeroInvoiceRepository $xeroInvoiceRepository
    ) {
        parent::__construct();
        $this->xeroInvoiceModelRepository = $xeroInvoiceModelRepository;
        $this->xeroInvoiceRepository = $xeroInvoiceRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info(date('Y-m-d H:i:s').' ======= Start - GenereateInvoice Command =======');
        \Log::channel('xeroLog')->info('======= Start - GenereateInvoice Command =======');

        try {
            $time_start = microtime(true);

            $payload = $this->payload();

            $xeroInvoices = $this->xeroInvoiceModelRepository->get($payload['id'], $payload['auctions'], $payload['customers'], $payload['type'], $payload['items'], $payload['for'], $payload['status']);

            $sellerItems = [];
            $buyerItems = [];

            if (sizeof($xeroInvoices) > 0) {

                foreach ($xeroInvoices as $xeroInvoiceByBuyer) {
                    foreach ($xeroInvoiceByBuyer as $xeroInvoice) {
                        $item = $xeroInvoice->item;
                        if ($payload['for'] == 'auction') {
                            if ($payload['type'] == 'all' || $payload['type'] == 'invoice') {
                                $buyerItems[$xeroInvoice->auction_id][$xeroInvoice->buyer_id]['items'][] = $item;
                                //  if($xeroInvoice->buyer->buyer_gst_status == 1){
                                    $price = $this->stringToNumber($xeroInvoice->sold_price_inclusive_gst);
                                //  }else{
                                //     $price = $this->stringToNumber($xeroInvoice->sold_price_exclusive_gst);
                                //  }
                                $buyerItems[$xeroInvoice->auction_id][$xeroInvoice->buyer_id]['prices'][] = $price;
                                $buyerItems[$xeroInvoice->auction_id][$xeroInvoice->buyer_id]['xeroInvoices'][] = $xeroInvoice;
                            }

                            if ($payload['type'] == 'all' || $payload['type'] == 'bill') {
                                $sellerItems[$xeroInvoice->auction_id][$xeroInvoice->seller_id]['items'][] = $item;
                                $sellerItems[$xeroInvoice->auction_id][$xeroInvoice->seller_id]['prices'][] = $xeroInvoice->price;
                                $sellerItems[$xeroInvoice->auction_id][$xeroInvoice->seller_id]['xeroInvoices'][] = $xeroInvoice;
                            }
                        }
                    }
                }
            }

            $this->passDataToXero($payload['for'], $payload['type'], $buyerItems, $sellerItems);

            $time_end = microtime(true);
            $execution_time = ($time_end - $time_start);

            $this->info(date('Y-m-d H:i:s').' ======= Duration '. $execution_time .' =======');
            \Log::channel('xeroLog')->info('======= Duration '. $execution_time .' =======');
            $this->info(date('Y-m-d H:i:s').' ======= End - GenereateInvoice Command =======');
            \Log::channel('xeroLog')->info('======= End - GenereateInvoice Command =======');

        } catch (\Throwable $th) {
            \Log::channel('xeroLog')->error('======= Failed - GenereateInvoice Command =======');
            \Log::channel('xeroLog')->error("Caught Exception ('{$th->getMessage()}')\n{$th}\n");

            throw $th;
        }
    }

    protected function passDataToXero($for, $type, $buyerItems, $sellerItems)
    {
        if ($for == 'auction') {
            if ($type == 'all' || $type == 'invoice') {
                $this->generateAuctionInvoice($buyerItems, $type);
            }

            if ($type == 'all' || $type == 'bill') {
                $this->generateAuctionBill($sellerItems, $type);
            }
        } else {
        }
    }

    protected function generateAuctionInvoice($buyerItems, $type = 'all')
    {
        if (sizeof($buyerItems) > 0) {
            foreach ($buyerItems as $auction_index => $buyerItemByAuction) {
                $auction = Auction::where('id', $auction_index)->first();

                $ref = $auction->title;
                foreach ($buyerItemByAuction as $customer_index => $buyerItem) {
                    $this->xeroInvoiceRepository->buyerAuctionXeroInvoice($customer_index, $buyerItem['items'], $buyerItem['prices'], $ref, $auction_index);
                    foreach ($buyerItem['xeroInvoices'] as $xeroInvoice) {
                        if($type == 'all'){
                            $xeroInvoice->status = 1;
                        }
                        if($type == 'invoice'){
                            $xeroInvoice->status = 2;
                        }
                        $xeroInvoice->save();
                    }
                }
            }
        }
    }

    protected function generateAuctionBill($sellerItems, $type = 'all')
    {
        if (sizeof($sellerItems) > 0) {
            foreach ($sellerItems as $auction_index => $sellerItemByAuction) {
                $auction = Auction::where('id', $auction_index)->first();

                $ref = $auction->title;
                foreach ($sellerItemByAuction as $customer_index => $sellerItem) {
                    $this->xeroInvoiceRepository->sellerXeroInvoice($customer_index, $sellerItem['items'], $sellerItem['prices'], $ref, $auction_index, 'auction', 'auction');
                    foreach ($sellerItem['xeroInvoices'] as $xeroInvoice) {
                        if($type == 'all'){
                            $xeroInvoice->status = 1;
                        }
                        if($type == 'bill'){
                            $xeroInvoice->status = 3;
                        }
                        $xeroInvoice->save();
                    }
                }
            }
        }
    }

    protected function payload()
    {
        $id = $this->option('id');

        $status = $this->option('status');

        $auctionOption = $this->option('auction');
        $auctions = [];
        foreach (explode(",", $auctionOption) as $eachAuction) {
            $auctions[] = $eachAuction;
        }

        if ($auctionOption == 'all') {
            $auctions = 'all';
        }

        $customerOption = $this->option('customer');

        $customers = [];
        foreach (explode(",", $customerOption) as $eachCustomer) {
            $customers[] = $eachCustomer;
        }

        if ($customerOption == 'all') {
            $customers = 'all';
        }

        $type = $this->option('type');

        $for = $this->option('for');

        $itemOption = $this->option('item');

        $items = [];
        foreach (explode(",", $itemOption) as $eachItem) {
            $items[] = $eachItem;
        }

        if ($itemOption == 'all') {
            $items = 'all';
        }

        return [
            'id' => $id,
            'status' => $status,
            'auctions' => $auctions,
            'customers' => $customers,
            'type' => $type,
            'for' => $for,
            'items' => $items
        ];
    }

    protected function stringToNumber($number)
    {
        return number_format($number, 4, '.', '');
    }
}
