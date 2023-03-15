<?php

namespace App\Console\Commands\gap;

use Illuminate\Console\Command;

use App\Helpers\NHelpers;

class Lot extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gap:lot';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Lot API';

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
        // $this->isAuctionExists();
        // $this->getLotsByAuctionId();
        // $this->getLotById();
        // $this->getSaleResultsByAuctionId();
        
        return $this->addImageUrlToLot();

        // $this->getAutoBidsByAuctionId(); DID NOT WORK
        // $this->getAutoBidsByLotId(); DID NOT WORK
    }

    private function isAuctionExists(){
        $config = NHelpers::getGapConfig();

        $apiInstance = new \GAP\Api\LotsApi(
            new \GuzzleHttp\Client(),
            $config
        );

        try {
            $auctionId = "2ce8821b-72d7-4b19-b92b-ab9f00aadc03";
            $result = $apiInstance->checkIfAuctionExist($auctionId);
            $this->info('Check If Auction Exist -> SUCCESS');
            \Log::info($result);
        } catch (Exception $e) {
            $this->error('Check If Auction Exist -> FAIL');
            \Log::error($e);
        }
    }

    // private function createLot(){
    //     $config = NHelpers::getGapConfig();

    //     $apiInstance = new \GAP\Api\LotsApi(
    //         new \GuzzleHttp\Client(),
    //         $config
    //     );

    //     try {
    //         $result = $apiInstance->();
    //         $this->info(' -> SUCCESS');
    //         \Log::info($result);
    //     } catch (Exception $e) {
    //         $this->error(' -> FAIL');
    //         \Log::error($e);
    //     }
    // }

    // private function updateLot(){
    //     $config = NHelpers::getGapConfig();

    //     $apiInstance = new \GAP\Api\LotsApi(
    //         new \GuzzleHttp\Client(),
    //         $config
    //     );

    //     try {
    //         $result = $apiInstance->();
    //         $this->info(' -> SUCCESS');
    //         \Log::info($result);
    //     } catch (Exception $e) {
    //         $this->error(' -> FAIL');
    //         \Log::error($e);
    //     }
    // }

    // private function updateLots(){
    //     $config = NHelpers::getGapConfig();

    //     $apiInstance = new \GAP\Api\LotsApi(
    //         new \GuzzleHttp\Client(),
    //         $config
    //     );

    //     try {
    //         $result = $apiInstance->();
    //         $this->info(' -> SUCCESS');
    //         \Log::info($result);
    //     } catch (Exception $e) {
    //         $this->error(' -> FAIL');
    //         \Log::error($e);
    //     }
    // }

    // private function deleteLot(){
    //     $config = NHelpers::getGapConfig();

    //     $apiInstance = new \GAP\Api\LotsApi(
    //         new \GuzzleHttp\Client(),
    //         $config
    //     );

    //     try {
    //         $result = $apiInstance->();
    //         $this->info(' -> SUCCESS');
    //         \Log::info($result);
    //     } catch (Exception $e) {
    //         $this->error(' -> FAIL');
    //         \Log::error($e);
    //     }
    // }

    // private function addBase64ImageToLot(){
    //     $config = NHelpers::getGapConfig();

    //     $apiInstance = new \GAP\Api\LotsApi(
    //         new \GuzzleHttp\Client(),
    //         $config
    //     );

    //     try {
    //         $result = $apiInstance->();
    //         $this->info(' -> SUCCESS');
    //         \Log::info($result);
    //     } catch (Exception $e) {
    //         $this->error(' -> FAIL');
    //         \Log::error($e);
    //     }
    // }

    private function addImageUrlToLot(){
        $config = NHelpers::getGapConfig();

        $apiInstance = new \GAP\Api\LotsApi(
            new \GuzzleHttp\Client(),
            $config
        );

        $img_data = [
            "LotId"=> "11963fb0-b3a7-4572-930f-abfe014967e0",
            "ImageName"=> "1593115334sell-1.jpg",
            "Url"=> asset("/storage/items/1593115334sell-1.jpg"),
            // "Url"=> "https://admin.hotlotz.nexlabs.co/storage/items/1593115334sell-1.jpg",
            // "Url"=> "https://admin.hotlotz.nexlabs.dev/storage/items/1593115396sell-1.jpg",
        ];

        try {
            $result = $apiInstance->addImageUrlToLot($img_data);
            $this->info(' -> SUCCESS');
            \Log::info($result);
            return $result;

        } catch (Exception $e) {
            $this->error(' -> FAIL');
            \Log::error($e);
        }
    }

    // private function setVideos(){
    //     $config = NHelpers::getGapConfig();

    //     $apiInstance = new \GAP\Api\LotsApi(
    //         new \GuzzleHttp\Client(),
    //         $config
    //     );

    //     try {
    //         $result = $apiInstance->();
    //         $this->info(' -> SUCCESS');
    //         \Log::info($result);
    //     } catch (Exception $e) {
    //         $this->error(' -> FAIL');
    //         \Log::error($e);
    //     }
    // }

    // private function removeLotImage(){}
    // $config = NHelpers::getGapConfig();

    //     $apiInstance = new \GAP\Api\LotsApi(
    //         new \GuzzleHttp\Client(),
    //         $config
    //     );

    //     try {
    //         $result = $apiInstance->();
    //         $this->info(' -> SUCCESS');
    //         \Log::info($result);
    //     } catch (Exception $e) {
    //         $this->error(' -> FAIL');
    //         \Log::error($e);
    //     }
    // priva
    // te function changeLotSaleSection(){
    //     $config = NHelpers::getGapConfig();

    //     $apiInstance = new \GAP\Api\LotsApi(
    //         new \GuzzleHttp\Client(),
    //         $config
    //     );

    //     try {
    //         $result = $apiInstance->();
    //         $this->info(' -> SUCCESS');
    //         \Log::info($result);
    //     } catch (Exception $e) {
    //         $this->error(' -> FAIL');
    //         \Log::error($e);
    //     }
    // }

    // private function setAutoBid(){
    //     $config = NHelpers::getGapConfig();

    //     $apiInstance = new \GAP\Api\LotsApi(
    //         new \GuzzleHttp\Client(),
    //         $config
    //     );

    //     try {
    //         $result = $apiInstance->();
    //         $this->info(' -> SUCCESS');
    //         \Log::info($result);
    //     } catch (Exception $e) {
    //         $this->error(' -> FAIL');
    //         \Log::error($e);
    //     }
    // }

    private function getAutoBidsByAuctionId(){
        $config = NHelpers::getGapConfig();

        $apiInstance = new \GAP\Api\LotsApi(
            new \GuzzleHttp\Client(),
            $config
        );

        try {
            $auctionId = "2ce8821b-72d7-4b19-b92b-ab9f00aadc03";
            $result = $apiInstance->getAutobidByAuctionId($auctionId);
            $this->info('Get Autobid By AuctionId -> SUCCESS');
            \Log::info($result);
        } catch (Exception $e) {
            $this->error('Get Autobid By AuctionId -> FAIL');
            \Log::error($e);
        }
    }

    // private function getAutoBidByBidderAndLotId(){
    //     $config = NHelpers::getGapConfig();

    //     $apiInstance = new \GAP\Api\LotsApi(
    //         new \GuzzleHttp\Client(),
    //         $config
    //     );

    //     try {
    //         $result = $apiInstance->();
    //         $this->info(' -> SUCCESS');
    //         \Log::info($result);
    //     } catch (Exception $e) {
    //         $this->error(' -> FAIL');
    //         \Log::error($e);
    //     }
    // }

    private function getAutoBidsByLotId(){
        $config = NHelpers::getGapConfig();

        $apiInstance = new \GAP\Api\LotsApi(
            new \GuzzleHttp\Client(),
            $config
        );

        try {
            $lotId = "e49ddda8-aa93-4ca0-a69e-aba000cad981";
            $result = $apiInstance->getAutobidByLotId($lotId);
            $this->info('Get Autobid By LotId -> SUCCESS');
            \Log::info($result);
        } catch (Exception $e) {
            $this->error('Get Autobid By LotId -> FAIL');
            \Log::error($e);
        }
    }

    // private function deleteAutoBid(){
    //     $config = NHelpers::getGapConfig();

    //     $apiInstance = new \GAP\Api\LotsApi(
    //         new \GuzzleHttp\Client(),
    //         $config
    //     );

    //     try {
    //         $result = $apiInstance->();
    //         $this->info(' -> SUCCESS');
    //         \Log::info($result);
    //     } catch (Exception $e) {
    //         $this->error(' -> FAIL');
    //         \Log::error($e);
    //     }
    // }

    private function getLotById(){
        $config = NHelpers::getGapConfig();

        $apiInstance = new \GAP\Api\LotsApi(
            new \GuzzleHttp\Client(),
            $config
        );

        try {
            $lotId = "e49ddda8-aa93-4ca0-a69e-aba000cad981";
            $result = $apiInstance->getLot($lotId);
            $this->info('Get Lot By Id -> SUCCESS');
            \Log::info($result);
        } catch (Exception $e) {
            $this->error('Get Lot By Id -> FAIL');
            \Log::error($e);
        }
    }

    private function getLotsByAuctionId(){
        $config = NHelpers::getGapConfig();

        $apiInstance = new \GAP\Api\LotsApi(
            new \GuzzleHttp\Client(),
            $config
        );

        try {
            $auctionId = "2ce8821b-72d7-4b19-b92b-ab9f00aadc03";
            $result = $apiInstance->getLotsByAuctionId($auctionId);
            $this->info('Get Lots By AuctionId -> SUCCESS');
            \Log::info($result);
        } catch (Exception $e) {
            $this->error('Get Lots By AuctionId -> FAIL');
            \Log::error($e);
        }
    }

    private function getSaleResultsByAuctionId(){
        $config = NHelpers::getGapConfig();

        $apiInstance = new \GAP\Api\LotsApi(
            new \GuzzleHttp\Client(),
            $config
        );

        try {
            $auctionId = "2ce8821b-72d7-4b19-b92b-ab9f00aadc03";
            $result = $apiInstance->getSaleResultByAuctionId($auctionId);
            $this->info('Get SaleResult By AuctionId -> SUCCESS');
            \Log::info($result);
        } catch (Exception $e) {
            $this->error('Get SaleResult By AuctionId -> FAIL');
            \Log::error($e);
        }
    }

    // private function setDeliveryCategory(){
    //     $config = NHelpers::getGapConfig();

    //     $apiInstance = new \GAP\Api\LotsApi(
    //         new \GuzzleHttp\Client(),
    //         $config
    //     );

    //     try {
    //         $result = $apiInstance->();
    //         $this->info(' -> SUCCESS');
    //         \Log::info($result);
    //     } catch (Exception $e) {
    //         $this->error(' -> FAIL');
    //         \Log::error($e);
    //     }
    // }

    // private function setDeliveryCategories(){
    //     $config = NHelpers::getGapConfig();

    //     $apiInstance = new \GAP\Api\LotsApi(
    //         new \GuzzleHttp\Client(),
    //         $config
    //     );

    //     try {
    //         $result = $apiInstance->();
    //         $this->info(' -> SUCCESS');
    //         \Log::info($result);
    //     } catch (Exception $e) {
    //         $this->error(' -> FAIL');
    //         \Log::error($e);
    //     }
    // }

    // private function setSequenceNumber(){}
    // $config = NHelpers::getGapConfig();

    //     $apiInstance = new \GAP\Api\LotsApi(
    //         new \GuzzleHttp\Client(),
    //         $config
    //     );

    //     try {
    //         $result = $apiInstance->();
    //         $this->info(' -> SUCCESS');
    //         \Log::info($result);
    //     } catch (Exception $e) {
    //         $this->error(' -> FAIL');
    //         \Log::error($e);
    //     }

}
