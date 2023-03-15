<?php

namespace App\Modules\Auction\Observers;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Modules\Auction\Models\Auction;

class AuctionObserver
{
    /**
     * Handle the post "saved" event.
     *
     * @param  \App\Modules\Auction\Models\Auction $auction
     * @return void
     */
    public function saved(Auction $auction)
    {
        if ($auction->wasRecentlyCreated) {
            $slug =  Str::slug($auction->title . '-' . date('Y-m-d H:i:s'), '-');
            DB::table('auctions')->where('id', $auction->id)->update(
                [
                    'coming_auction_url' => $slug
                ]
            );
        }
        if ($auction->wasChanged('title')) {
            $slug =  Str::slug($auction->title . '-' . date('Y-m-d H:i:s'), '-');
            DB::table('auctions')->where('id', $auction->id)->update(
                [
                    'coming_auction_url' => $slug
                ]
            );
        }
    }
}
