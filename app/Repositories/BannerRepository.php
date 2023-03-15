<?php

namespace App\Repositories;

use DB;

use App\Helpers\SampleHelper;

use App\Models\GeneralInfo;

use App\Modules\HomePage\Models\HomePageBanner;
use App\Modules\AuctionCms\Models\AuctionCms;
use App\Modules\MarketplaceCms\Models\MarketplaceCms;
use App\Modules\PrivateCollections\Models\PrivateCollections;
use App\Modules\HomeContent\Models\HomeContent;
use App\Modules\BusinessSeller\Models\BusinessSeller;
use App\Modules\HotlotzConcierge\Models\HotlotzConcierge;
use App\Modules\ShippingAndStorage\Models\ShippingAndStorage;
use App\Modules\WhatWeSell\Models\WhatWeSell;
use App\Modules\WhatWeSells\Models\WhatWeSells;
use App\Modules\AuctionMainPage\Models\AuctionResultsMain;
use App\Modules\AuctionMainPage\Models\PastCataloguesMain;
use App\Modules\HomePage\Models\HomePageMarketplaceBanner;
use App\Modules\SellWithUs\Models\SellWithUsBlog;
use App\Modules\MarketplaceHomeBanner\Models\MarketplaceHomeBanner;
use App\Modules\MarketplaceHome\Models\MarketplaceHomeSubstainableSourcing;
use App\Modules\MarketplaceHome\Models\MarketplaceCollaboration;
use App\Modules\LocationCms\Models\LocationCms;
use App\Modules\AboutUs\Models\AboutUs;
use App\Modules\HowToBuy\Models\HowToBuy;
use App\Modules\HowToSell\Models\HowToSell;
use App\Modules\ProfessionalValuations\Models\ProfessionalValuations;
use App\Modules\StrategicPartner\Models\StrategicPartnerInfo;
use App\Modules\Careers\Models\CareersInfo;
use App\Modules\MediaResource\Models\MediaResource;
use App\Modules\Faq\Models\FaqInfo;
use App\Modules\OurTeam\Models\OurTeamInfo;
use App\Modules\MarketplaceHome\Models\MarketplaceCollabrationInfo;
use App\Modules\MainBanner\Models\MainBanner;
use App\Modules\MarketplaceBanner\Models\MarketplaceBanner;
use App\Modules\MarketplaceMainBanner\Models\MarketplaceMainBanner;

class BannerRepository
{

    public function __construct(){

    }

    public function getRandomImage(){
        $result = [];
        $result['image'] = SampleHelper::getRandomImage();
        return $result;
    }

    public function getRandomBanner(){
        $result = [];
        $result['image'] = SampleHelper::getRandomBanner();
        $result['caption'] = "Hotlotz caption here";
        return $result;
    }

    public function getMainBanners(){
        $mainBanners = collect();
        $main_banners = MainBanner::orderBy('order')->get();

        foreach($main_banners as $homepage){
            $mainBanner = [
                "mainTitle" => $homepage->main_title,
                "caption" => $homepage->sub_title,
                "image" => $homepage->full_path,
                "link" => $homepage->link,
                "position" => $homepage->position,
                "color" => $homepage->color,
                "linkName" => $homepage->link_name,
            ];
            $mainBanners[] = $mainBanner;
        }

        return $mainBanners;
    }

    public function getMarketplaceBanners(){ // For home page's marketplace banner
        $mainBanners = collect();
        $marketplace_banners = MarketplaceBanner::orderBy('order')->get();

        foreach($marketplace_banners as $homepage){
            $mainBanner = [
                // "caption" => $homepage->caption,
                "image" => $homepage->full_path,
            ];
            $mainBanners[] = $mainBanner;
        }

        return $mainBanners;
    }

    public function getAuctionResult(){

        $infos = AuctionResultsMain::all();

        $banner = '';
        $caption = '';

        if (!$infos->isEmpty()){
            $info = $infos->first();

            $banner = $info->banner_image;
            $caption = $info->caption;
        }

        $result = [];
        $result['image'] = $banner;
        $result['caption'] = $caption;

        return $result;

    }

    public function getPastCatalogues(){
        $infos = PastCataloguesMain::all();

        $banner = '';
        $caption = '';

        if (!$infos->isEmpty()){
            $info = $infos->first();

            $banner = $info->banner_image;
            $caption = $info->caption;
        }

        $result = [];
        $result['image'] = $banner;
        $result['caption'] = $caption;

        return $result;
    }

    public function getMarketplaceHomeBanners(){
        $mainBanners = collect();
        $marketplace_main_banners = MarketplaceMainBanner::orderBy('order')->get();

        foreach($marketplace_main_banners as $homepage){
            $mainBanner = [
                "caption" => $homepage->caption,
                "image" => $homepage->full_path,
                "learn_more" => $homepage->learn_more,
            ];
            $mainBanners[] = $mainBanner;
        }

        return $mainBanners;
    }

    public function getCollaborationBanner(){

        $mainBanners = MarketplaceCollaboration::get();

        $data = [];
        if (!$mainBanners->isEmpty()) {
            foreach ($mainBanners as $key => $value) {
                $data[] = [
                    'slogan' => $value->slogan,
                    'caption' => $value->header_title,
                    'image' => $value->file_path,
                    'link' => route('marketplace.collaborations')
                ];
            }
            $data = collect($data);
        }

        return $data;
    }

    public function getMarketplaceSustainableSourcingBanners(){
        $mainBanners = collect();

        foreach(MarketplaceHomeSubstainableSourcing::all() as $homepage){
            $mainBanner = [
                "image" => $homepage->file_path,
                "link" => $homepage->link,
            ];
            $mainBanners[] = $mainBanner;
        }

        return $mainBanners;
    }

    public function getFAQ(){
        $faq_info_data = FaqInfo::all();

        $banner = '';
        $caption = '';

        if (!$faq_info_data->isEmpty()){
            $faq_info = $faq_info_data->first();

            $banner = $faq_info->banner_image;
            $caption = $faq_info->caption;
        }

        $result = [];
        $result['image'] = $banner;
        $result['caption'] = $caption;

        return $result;
    }

    public function getStrategicPartner(){
        $strategic_partners_data = StrategicPartnerInfo::all();

        $banner = '';
        $caption = '';

        if (!$strategic_partners_data->isEmpty()){
            $strategic_partners = $strategic_partners_data->first();

            $banner = $strategic_partners->banner_image;
            $caption = $strategic_partners->caption;
        }

        $result = [];
        $result['image'] = $banner;
        $result['caption'] = $caption;

        return $result;
    }

    public function getWhatWeSell(){
        $info =  $this->getResult(['whatwesell_info','whatwesell_banner']);

        $result = [];
        $result['image'] = $info['whatwesell_banner'];
        $result['caption'] = $info['whatwesell_info'];
        return $result;
    }

    public function getWhatWeSellDetail($id){
        $whatwesell_data = WhatWeSells::find($id);

        $result = [];
        // $result['image'] = $whatwesell_data['list_banner_image_file_path'];
        $result['image'] = $whatwesell_data['detail_banner_full_path'];
        $result['caption'] = $whatwesell_data['caption'];
        return $result;
    }

    public function getAuction(){

        $auction_cms_data = AuctionCms::all();

        $banner = '';
        $caption = '';

        if (!$auction_cms_data->isEmpty()){
            $auction_cms = $auction_cms_data->first();

            $banner = $auction_cms->banner_image;
            $caption = $auction_cms->caption;
        }

        $result = [];
        $result['image'] = $banner;
        $result['caption'] = $caption;

        return $result;
    }

    public function getMarketplace(){

        $cmsData = MarketplaceCms::all();

        $banner = '';
        $caption = '';

        if (!$cmsData->isEmpty()){
            $cms = $cmsData->first();

            $banner = $cms->banner_image;
            $caption = $cms->caption;
        }

        $result = [];
        $result['image'] = $banner;
        $result['caption'] = $caption;

        return $result;
    }

    public function getPrivateCollection(){

        $cmsData = PrivateCollections::all();

        $banner = '';
        $caption = '';

        if (!$cmsData->isEmpty()){
            $cms = $cmsData->first();

            $banner = $cms->banner_image;
            $caption = $cms->caption;
        }

        $result = [];
        $result['image'] = $banner;
        $result['caption'] = $caption;

        return $result;
    }

    public function getHomeContent(){

        $cmsData = HomeContent::all();

        $banner = '';
        $caption = '';

        if (!$cmsData->isEmpty()){
            $cms = $cmsData->first();

            $banner = $cms->banner_image;
            $caption = $cms->caption;
        }

        $result = [];
        $result['image'] = $banner;
        $result['caption'] = $caption;

        return $result;
    }

    public function getBusinessSeller(){

        $cmsData = BusinessSeller::all();

        $banner = '';
        $caption = '';

        if (!$cmsData->isEmpty()){
            $cms = $cmsData->first();

            $banner = $cms->banner_image;
            $caption = $cms->caption;
        }

        $result = [];
        $result['image'] = $banner;
        $result['caption'] = $caption;

        return $result;
    }

    public function getProfessionalValuation(){

        $professional_valuations_data = ProfessionalValuations::all();

        $banner = '';
        $caption = '';

        if (!$professional_valuations_data->isEmpty()){
            $professional_valuations = $professional_valuations_data->first();

            $banner = $professional_valuations->banner_image;
            $caption = $professional_valuations->caption;
        }

        $result = [];
        $result['image'] = $banner;
        $result['caption'] = $caption;

        return $result;
    }

    public function getHotlotzConcierge(){

        $cmsData = HotlotzConcierge::all();

        $banner = '';
        $caption = '';

        if (!$cmsData->isEmpty()){
            $cms = $cmsData->first();

            $banner = $cms->banner_image;
            $caption = $cms->caption;
        }

        $result = [];
        $result['image'] = $banner;
        $result['caption'] = $caption;

        return $result;
    }

    public function getCollectionShipping(){

        $cmsData = ShippingAndStorage::all();

        $banner = '';
        $caption = '';

        if (!$cmsData->isEmpty()){
            $cms = $cmsData->first();

            $banner = $cms->banner_image;
            $caption = $cms->caption;
        }

        $result = [];
        $result['image'] = $banner;
        $result['caption'] = $caption;

        return $result;
    }

    private function getResult($keys){
        $infos = GeneralInfo::all()->whereIn('key', $keys);

        $result = [];

        foreach($keys as $key){
            $result[$key] = '';
            foreach($infos as $info){
                if($info->key == $key)
                    $result[$key] = $info->value;
            }
        }

        return $result;
    }

    public function getSellWithUs(){


        $cmsData = SellWithUsBlog::all();

        $banner = '';
        $caption = '';

        if (!$cmsData->isEmpty()){
            $cms = $cmsData->first();

            $banner = $cms->banner_image;
            $caption = "";
        }

        $result = [];
        $result['image'] = $banner;
        $result['caption'] = $caption;

        return $result;
    }

    public function getLocation(){

        $location_cms_data = LocationCms::all();

        $banner = '';
        $caption = '';

        if (!$location_cms_data->isEmpty()){
            $location_cms = $location_cms_data->first();

            $banner = $location_cms->banner;
            $caption = $location_cms->caption;
        }

        $result = [];
        $result['image'] = $banner;
        $result['caption'] = $caption;

        return $result;
    }

    public function getAboutus(){

        $aboutus_cms_data = AboutUs::all();

        $banner = '';
        $caption = '';

        if (!$aboutus_cms_data->isEmpty()){
            $aboutus_cms = $aboutus_cms_data->first();

            $banner = $aboutus_cms->banner_image;
            $caption = $aboutus_cms->caption;
        }

        $result = [];
        $result['image'] = $banner;
        $result['caption'] = $caption;

        return $result;
    }

    public function getHowToBuy(){

        $howtobuy_cms_data = HowToBuy::all();

        $banner = '';
        $caption = '';

        if (!$howtobuy_cms_data->isEmpty()){
            $howtobuy_cms = $howtobuy_cms_data->first();

            $banner = $howtobuy_cms->banner_image;
            $caption = $howtobuy_cms->caption;
        }

        $result = [];
        $result['image'] = $banner;
        $result['caption'] = $caption;

        return $result;
    }

    public function getHowToSell(){

        $howtosell_cms_data = HowToSell::all();

        $banner = '';
        $caption = '';

        if (!$howtosell_cms_data->isEmpty()){
            $howtosell_cms = $howtosell_cms_data->first();

            $banner = $howtosell_cms->banner_image;
            $caption = $howtosell_cms->caption;
        }

        $result = [];
        $result['image'] = $banner;
        $result['caption'] = $caption;

        return $result;
    }

    public function getCareersInfo(){

        $careers_cms_data = CareersInfo::all();

        $banner = '';
        $caption = '';

        if (!$careers_cms_data->isEmpty()){
            $careers_cms = $careers_cms_data->first();

            $banner = $careers_cms->banner_image;
            $caption = $careers_cms->caption;
        }

        $result = [];
        $result['image'] = $banner;
        $result['caption'] = $caption;

        return $result;
    }

    public function getMediaResource(){

        $media_resource_cms_data = MediaResource::all();

        $banner = '';
        $caption = '';

        if (!$media_resource_cms_data->isEmpty()){
            $media_resource = $media_resource_cms_data->first();

            $banner = $media_resource->banner_image;
            $caption = $media_resource->caption;
        }

        $result = [];
        $result['image'] = $banner;
        $result['caption'] = $caption;

        return $result;
    }

    public function getCollabrationCategoryBanners(){

        $mainBanners = MarketplaceCollaboration::all();

        $data = [];
        if (!$mainBanners->isEmpty()) {
            foreach ($mainBanners as $key => $value) {
                $data[] = [
                    'slogan' => $value->slogan,
                    'caption' => $value->header_title,
                    'image' => $value->file_path,
                    'link' => route('marketplace.collaborations')
                ];
            }
            $data = collect($data);
        }

        return $data;
    }

    public function getOurTeam(){
        $team_info_data = OurTeamInfo::all();

        $banner = '';
        $caption = '';

        if (!$team_info_data->isEmpty()){
            $team_info = $team_info_data->first();

            $banner = $team_info->banner_image;
            $caption = $team_info->caption;
        }

        $result = [];
        $result['image'] = $banner;
        $result['caption'] = $caption;

        return $result;
    }

    public function getCollabrationPageBanner(){

        $cmsData = MarketplaceCollabrationInfo::all();

        $banner = '';
        $caption = '';

        if (!$cmsData->isEmpty()){
            $cms = $cmsData->first();

            $banner = $cms->banner_image;
            $caption = $cms->caption;
        }

        $result = [];
        $result['image'] = $banner;
        $result['caption'] = $caption;

        return $result;
    }

}
