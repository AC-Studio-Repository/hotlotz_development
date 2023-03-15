<?php

namespace App\Repositories;

use DB;

use App\Helpers\SampleHelper;

use App\Models\GeneralInfo;

use App\Modules\SellWithUs\Models\SellWithUsBlog;
use App\Modules\SellWithUs\Models\SellWithUsFaq;
use App\Modules\AuctionMainPage\Models\AuctionResultsMain;
use App\Modules\AuctionMainPage\Models\PastCataloguesMain;
use App\Modules\LocationCms\Models\LocationCms;
use App\Modules\Faq\Models\FaqInfo;
use App\Modules\MarketplaceHome\Models\MarketplaceCollaborationBlog;

use App\Modules\OurTeam\Models\OurTeam;
use App\Modules\OurTeam\Models\OurTeamInfo;

use App\Modules\AuctionCms\Models\AuctionCms;
use App\Modules\AuctionCms\Models\AuctionCmsBlog;

use App\Modules\AboutUs\Models\AboutUs;
use App\Modules\AboutUs\Models\AboutUsBlog;

use App\Modules\HowToBuy\Models\HowToBuy;
use App\Modules\HowToBuy\Models\HowToBuyBlog;

use App\Modules\HowToSell\Models\HowToSell;
use App\Modules\HowToSell\Models\HowToSellBlog;

use App\Modules\MarketplaceCms\Models\MarketplaceCms;
use App\Modules\MarketplaceCms\Models\MarketplaceCmsBlog;

use App\Modules\PrivateCollections\Models\PrivateCollections;
use App\Modules\PrivateCollections\Models\PrivateCollectionsBlog;

use App\Modules\HomeContent\Models\HomeContent;
use App\Modules\HomeContent\Models\HomeContentBlog;

use App\Modules\BusinessSeller\Models\BusinessSeller;
use App\Modules\BusinessSeller\Models\BusinessSellerBlog;

use App\Modules\HotlotzConcierge\Models\HotlotzConcierge;
use App\Modules\HotlotzConcierge\Models\HotlotzConciergeBlog;

use App\Modules\ShippingAndStorage\Models\ShippingAndStorage;
use App\Modules\ShippingAndStorage\Models\ShippingAndStorageBlog;

use App\Modules\ProfessionalValuations\Models\ProfessionalValuations;
use App\Modules\ProfessionalValuations\Models\ProfessionalValuationsBlog;

use App\Modules\StrategicPartner\Models\StrategicPartnerInfo;
use App\Modules\StrategicPartner\Models\StrategicPartnerInfoBlog;

use App\Modules\WhatWeSell\Models\WhatWeSell;
use App\Modules\WhatWeSell\Models\WhatWeSellBlog;

use App\Modules\WhatWeSells\Models\WhatWeSells;

use App\Modules\Careers\Models\CareersInfo;
use App\Modules\Careers\Models\CareersBlog;

use App\Modules\MediaResource\Models\MediaResource;
use App\Modules\MediaResource\Models\MediaResourceBlog;

use App\Modules\MarketplaceHome\Models\MarketplaceItemDetailPolicy;

class CMSRepository
{

    public function __construct(){

    }

    public function getAuctionResult(){

        $result = [];

        $result['title_header'] = "";
        $result['title_blog'] = "";

        foreach(AuctionResultsMain::all() as $cms){
            $result['title_header'] = $cms->title_header;
            $result['title_blog'] = $cms->title_blog;
        }

        return $result;
    }

    public function getPastCatalogues(){

        $result = [];

        $result['title_header'] = "";
        $result['title_blog'] = "";

        foreach(PastCataloguesMain::all() as $cms){
            $result['title_header'] = $cms->title_header;
            $result['title_blog'] = $cms->title_blog;
        }

        return $result;
    }

    public function getAboutUs(){
        $result = [];

        $result['title_header'] = "";
        $result['title_blog'] = "";

        foreach(AboutUs::all() as $cms){
            $result['title_header'] = $cms->blog_header_1;
            $result['title_blog'] = $cms->blog_1;
        }

        return $result;
    }

    public function getHowToBuy(){
        $result = [];

        $result['title_header'] = "";
        $result['title_blog'] = "";
        $result['download_file'] = "";

        foreach(HowToBuy::all() as $cms){
            $result['title_header'] = $cms->blog_header_1;
            $result['title_blog'] = $cms->blog_1;
            $result['download_file'] = $cms->uploaded_filen_path;
        }

        return $result;
    }

    public function getHowToSell(){
        $result = [];

        $result['title_header'] = "";
        $result['title_blog'] = "";

        foreach(HowToSell::all() as $cms){
            $result['title_header'] = $cms->blog_header_1;
            $result['title_blog'] = $cms->blog_1;
        }

        return $result;
    }

    public function getPartners(){
        $result = [];

        return $result;
    }

    public function getFAQ(){
        $result = [];

        $result['title_header'] = "";
        $result['title_blog'] = "";

        foreach(FaqInfo::all() as $cms){
            $result['title_header'] = $cms->title_header;
            $result['title_blog'] = $cms->title_blog;
        }

        return $result;
    }

    public function getLocation(){
        $result = [];

        $result['title_header'] = "";
        $result['title_blog'] = "";
        $result['direction_header'] = "";
        $result['direction_blog'] = "";
        $result['saleroom_details'] = "";
        $result['mon'] = "";
        $result['tue'] = "";
        $result['wed'] = "";
        $result['thur'] = "";
        $result['fri'] = "";
        $result['sat'] = "";
        $result['sun'] = "";

        foreach(LocationCms::all() as $cms){
            $result['title_header'] = $cms->title_header;
            $result['title_blog'] = $cms->title_blog;
            $result['direction_header'] = $cms->direction_header;
            $result['direction_blog'] = $cms->direction_blog;
            $result['saleroom_details'] = $cms->saleroom_details;
            $result['mon'] = $cms->mon;
            $result['tue'] = $cms->tue;
            $result['wed'] = $cms->wed;
            $result['thur'] = $cms->thur;
            $result['fri'] = $cms->fri;
            $result['sat'] = $cms->sat;
            $result['sun'] = $cms->sun;
        }

        return $result;
    }

    public function getTeam(){
        $result = [];

        return $result;
    }

    public function getGlossary(){
        $result = [];

        return $result;
    }

    public function getWhatWeSell($id){
        $result = [];

        $what_we_sell = WhatWeSells::find($id);

        $result['title_header'] = "";
        $result['title_blog'] = "";

        if(isset($what_we_sell)){
            $result['title_header'] = $what_we_sell->title;
            $result['title_blog'] = $what_we_sell->description;
        }

        return $result;
    }

    public function getAuction(){
        $result = [];

        $result['title_header'] = "";
        $result['title_blog'] = "";

        foreach(AuctionCms::all() as $cms){
            $result['title_header'] = $cms->blog_header_1;
            $result['title_blog'] = $cms->blog_1;
        }

        return $result;
    }

    public function getMarketplace(){
        $result = [];

        $result['title_header'] = "";
        $result['title_blog'] = "";

        foreach(MarketplaceCms::all() as $cms){
            $result['title_header'] = $cms->blog_header_1;
            $result['title_blog'] = $cms->blog_1;
        }

        return $result;
    }

    public function getPrivateCollection(){
        $result = [];

        $result['title_header'] = "";
        $result['title_blog'] = "";

        foreach(PrivateCollections::all() as $cms){
            $result['title_header'] = $cms->blog_header_1;
            $result['title_blog'] = $cms->blog_1;
        }

        return $result;
    }

    public function getHomeContent(){
        $result = [];

        $result['title_header'] = "";
        $result['title_blog'] = "";

        foreach(HomeContent::all() as $cms){
            $result['title_header'] = $cms->blog_header_1;
            $result['title_blog'] = $cms->blog_1;
        }

        return $result;
    }

    public function getBusinessSeller(){
        $result = [];

        $result['title_header'] = "";
        $result['title_blog'] = "";

        foreach(BusinessSeller::all() as $cms){
            $result['title_header'] = $cms->blog_header_1;
            $result['title_blog'] = $cms->blog_1;
        }

        return $result;
    }

    public function getProfessionalValuation(){
        $result = [];

        $result['title_header'] = "";
        $result['title_blog'] = "";

        foreach(ProfessionalValuations::all() as $cms){
            $result['title_header'] = $cms->title;
            $result['title_blog'] = $cms->blog;
        }

        return $result;
    }

    public function getProfessionalValuationKeyContact(){
        $data = [];

        foreach(ProfessionalValuations::all() as $cms){
            $data[] = [
                'contactName' => $cms->key_contact_name,
                'contactPosition' => $cms->key_contact_position,
                'photoPath' => $cms->key_contact_image,
                'contactEmail' => $cms->key_contact_email
            ];    
        }

        $data = collect($data);
        return $data;
    }

    public function getHotlotzConcierge(){
        $result = [];

        $result['title_header'] = "";
        $result['title_blog'] = "";

        foreach(HotlotzConcierge::all() as $cms){
            $result['title_header'] = $cms->blog_header_1;
            $result['title_blog'] = $cms->blog_1;
        }

        return $result;
    }

    public function getCollectionShipping(){
        $result = [];

        $result['title_header'] = "";
        $result['title_blog'] = "";

        foreach(ShippingAndStorage::all() as $cms){
            $result['title_header'] = $cms->blog_header_1;
            $result['title_blog'] = $cms->blog_1;
        }

        return $result;
    }

    public function getSellWithUs(){
        $result = [];

        $result['blog_header_1'] = "";
        $result['blog_header_2'] = "";
        $result['blog_1'] = "";
        $result['blog_2'] = "";

        foreach(SellWithUsBlog::all() as $cms){
            $result['blog_header_1'] = $cms->blog_header_1;
            $result['blog_1'] = $cms->blog_1;
            $result['blog_header_2'] = $cms->blog_header_2;
            $result['blog_2'] = $cms->blog_2;
        }

        return $result;
    }

    public function getStrategicPartner(){
        $result = [];

        $result['title_header'] = "";
        $result['title_blog'] = "";

        foreach(StrategicPartnerInfo::all() as $cms){
            $result['title_header'] = $cms->blog_header_1;
            $result['title_blog'] = $cms->blog_1;
        }

        return $result;
    }

    public function getSellWithUsFAQ($limit=4, $offset=0){
        return SellWithUsFaq::take($limit)->offset($offset)->get();
    }

    public function getAuctionBlog(){
        $result = [];

        foreach(AuctionCmsBlog::all() as $cms){
            $result[] = [
                    'title' => $cms->title,
                    "blog" => $cms->blog
                ];
        }

        return $result;
    }

    public function getAboutusBlog(){
        $result = [];

        foreach(AboutUsBlog::all() as $cms){
            $result[] = [
                    'title' => $cms->title,
                    "blog" => $cms->blog
                ];
        }

        return $result;
    }

    public function getHowToBuyBlog(){
        $result = [];

        foreach(HowToBuyBlog::all() as $cms){
            $result[] = [
                    'title' => $cms->title,
                    "blog" => $cms->blog
                ];
        }

        return $result;
    }

    public function getHowToSellBlog(){
        $result = [];

        foreach(HowToSellBlog::all() as $cms){
            $result[] = [
                    'title' => $cms->title,
                    "blog" => $cms->blog
                ];
        }

        return $result;
    }

    public function getMarketplaceBlog(){
        $result = [];

        foreach(MarketplaceCmsBlog::all() as $cms){
            $result[] = [
                    'title' => $cms->title,
                    "blog" => $cms->blog
                ];
        }

        return $result;
    }

    public function getPrivateCollectionBlog(){
        $result = [];

        foreach(PrivateCollectionsBlog::all() as $cms){
            $result[] = [
                    'title' => $cms->title,
                    "blog" => $cms->blog
                ];
        }

        return $result;
    }

    public function getHomeContentBlog(){
        $result = [];

        foreach(HomeContentBlog::all() as $cms){
            $result[] = [
                    'title' => $cms->title,
                    "blog" => $cms->blog
                ];
        }

        return $result;
    }

    public function getBusinessSellerBlog(){
        $result = [];

        foreach(BusinessSellerBlog::all() as $cms){
            $result[] = [
                    'title' => $cms->title,
                    "blog" => $cms->blog
                ];
        }

        return $result;
    }

    public function getHotlotzConciergeBlog(){
        $result = [];

        foreach(HotlotzConciergeBlog::all() as $cms){
            $result[] = [
                    'title' => $cms->title,
                    "blog" => $cms->blog
                ];
        }

        return $result;
    }

    public function getCollectionShippingBlog(){
        $result = [];

        foreach(ShippingAndStorageBlog::all() as $cms){
            $result[] = [
                    'title' => $cms->title,
                    "blog" => $cms->blog
                ];
        }

        return $result;
    }

    public function getProfessionalValuationBlog(){
        $result = [];

        foreach(ProfessionalValuationsBlog::all() as $cms){
            $result[] = [
                    'title' => $cms->title,
                    "blog" => $cms->blog
                ];
        }

        return $result;
    }

    public function getStrategicPartnerBlog(){
        $result = [];

        foreach(StrategicPartnerInfoBlog::all() as $cms){
            $result[] = [
                    'title' => $cms->title,
                    "blog" => $cms->blog
                ];
        }

        return $result;
    }

    public function getWhatWeSellBlog($id){
        $result = [];

        $what_we_sell_blog_data = WhatWeSellBlog::where('what_we_sell_id', '=', $id)->get();

        foreach($what_we_sell_blog_data as $cms){
            $result[] = [
                    'title' => $cms->title,
                    "blog" => $cms->blog
                ];
        }

        return $result;
    }

    public function getWhatWeSellKeyContact($id){
        $data = [];

        $what_we_sell = WhatWeSells::find($id);

        if(isset($what_we_sell)){
            $team_1_data = [];
            if($what_we_sell->key_contact_1 != 0) {
                $team_1_data = OurTeam::where('id', '=', $what_we_sell->key_contact_1)->first();
            }     
            if($team_1_data) {
                $data[] = [
                    'contactName' => $team_1_data->name,
                    'contactPosition' => $team_1_data->position,
                    'photoPath' => $team_1_data->full_path,
                    'contactEmail' => $team_1_data->contact_email
                ];  
            }
            
            $team_2_data = [];
            if($what_we_sell->key_contact_2 != 0) {
                $team_2_data = OurTeam::where('id', '=', $what_we_sell->key_contact_2)->first();
            }
            if($team_2_data) {
                $data[] = [
                    'contactName' => $team_2_data->name,
                    'contactPosition' => $team_2_data->position,
                    'photoPath' => $team_2_data->full_path,
                    'contactEmail' => $team_2_data->contact_email
                ];  
            }   
        }

        $data = collect($data);
        return $data;
    }

    public function getCareersInfo(){
        $result = [];

        $result['title_header'] = "";
        $result['title_blog'] = "";

        foreach(CareersInfo::all() as $cms){
            $result['title_header'] = $cms->blog_header_1;
            $result['title_blog'] = $cms->blog_1;
        }

        return $result;
    }

    public function getCareerBlog(){
        $result = [];

        foreach(CareersBlog::all() as $cms){
            $result[] = [
                    'title' => $cms->title,
                    "blog" => $cms->blog
                ];
        }

        return $result;
    }

    public function getMediaResource(){
        $result = [];

        $result['title_header'] = "";
        $result['title_blog'] = "";
        $result['country_1'] = "";
        $result['email_1'] = "";
        $result['country_2'] = "";
        $result['email_2'] = "";
        $result['file_path'] = "";

        foreach(MediaResource::all() as $cms){
            $result['title_header'] = $cms->blog_header_1;
            $result['title_blog'] = $cms->blog_1;
            $result['country_1'] = $cms->contact_country_1;
            $result['email_1'] = $cms->contact_email_1;
            $result['country_2'] = $cms->contact_country_2;
            $result['email_2'] = $cms->contact_email_2;
            $result['file_path'] = $cms->our_asset_file_path;
        }

        return $result;
    }

    public function getMediaResourceBlog(){
        $result = [];

        foreach(MediaResourceBlog::all() as $cms){
            $result[] = [
                    'title' => $cms->title,
                    "blog" => $cms->blog
                ];
        }

        return $result;
    }

    public function getCollabrationBlog() {
        $result = [];

        foreach(MarketplaceCollaborationBlog::all() as $cms){
            $result[] = [
                    'title' => $cms->title,
                    "blog" => $cms->blog
                ];
        }

        return $result;
    }

    public function getOurTeam(){
        $result = [];

        $result['title_header'] = "";
        $result['title_blog'] = "";

        foreach(OurTeamInfo::all() as $cms){
            $result['title_header'] = $cms->title_header;
            $result['title_blog'] = $cms->title_blog;
        }

        return $result;
    }

    public function getKeyContact($slug){
        $result = [];
        if($slug == 'private-collections') {
            $result = PrivateCollections::all();
        }else if($slug == 'business-seller') {
            $result = BusinessSeller::all();
        }else if($slug == 'home-contents') {
            $result = HomeContent::all();
        }else if($slug == 'hotlotz-concierge') {
            $result = HotlotzConcierge::all();
        }else if($slug == 'professional-valuations') {
            $result = ProfessionalValuations::all();
        }

        $data = [];
        foreach($result as $cms){
            $team_1_data = [];
            if($cms->key_contact_1 != 0) {
                $team_1_data = OurTeam::where('id', '=', $cms->key_contact_1)->first();
            }     
            if($team_1_data) {
                $data[] = [
                    'contactName' => $team_1_data->name,
                    'contactPosition' => $team_1_data->position,
                    'photoPath' => $team_1_data->full_path,
                    'contactEmail' => $team_1_data->contact_email
                ];  
            }
            
            $team_2_data = [];
            if($cms->key_contact_2 != 0) {
                $team_2_data = OurTeam::where('id', '=', $cms->key_contact_2)->first();
            }
            if($team_2_data) {
                $data[] = [
                    'contactName' => $team_2_data->name,
                    'contactPosition' => $team_2_data->position,
                    'photoPath' => $team_2_data->full_path,
                    'contactEmail' => $team_2_data->contact_email
                ];  
            }
        }

        $data = collect($data);
        return $data;
    }

    public function getItemDetailPolicy(){
        $result = [];

        $result['collection_Shipping_header'] = "";
        $result['collection_Shipping_blog'] = "";
        $result['one_tree_planted_header'] = "";
        $result['one_tree_planted_blog'] = "";
        $result['sale_policy_header'] = "";
        $result['sale_policy_blog'] = "";

        foreach(MarketplaceItemDetailPolicy::all() as $cms){
            $result['collection_Shipping_header'] = $cms->collection_Shipping_header;
            $result['collection_Shipping_blog'] = $cms->collection_Shipping_blog;
            $result['one_tree_planted_header'] = $cms->one_tree_planted_header;
            $result['one_tree_planted_blog'] = $cms->one_tree_planted_blog;
            $result['sale_policy_header'] = $cms->sale_policy_header;
            $result['sale_policy_blog'] = $cms->sale_policy_blog;
        }

        return $result;
    }
}
