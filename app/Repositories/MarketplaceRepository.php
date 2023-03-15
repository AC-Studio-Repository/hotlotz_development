<?php

namespace App\Repositories;

use DB;
use App\Helpers\SampleHelper;
use App\Modules\WhatWeSell\Models\WhatWeSell;
use App\Modules\WhatWeSells\Models\WhatWeSells;

class MarketplaceRepository
{
    public function __construct()
    {
    }

    public function getMarketCategories()
    {
        $category = collect([
            "New Arrivals", "Maps", "Art", "Collectibles", "Bags", "Fashion", "Furniture", "Home Décor", "Jewellery", "Watches", "Rugs", "Antique & Fine", "Clearance"
        ]);
        return $category;
    }

    public function getCategories()
    {
        $categories = WhatWeSells::orderBy('order')->get();

        $data = [];
        if (!$categories->isEmpty()) {
            foreach ($categories as $key => $value) {
                $data[] = [
                    'imgPath' => $value->full_path,
                    'caption' => $value->category->name,//modified by mct[10May22]
                    'type' => $value->category->name,//modified by mct[5May22]
                    'link' => route('marketplace.list', ['type' => $value->category_id])//added by mct[5May22]
                    // 'link' => route('marketplace.list', ['type' => $this->getCategorySlug($value->category_id)]) //command out by mct[5May22]
                ];
            }
            $data = collect($data);
        }
        return $data;
    }

    protected function getCategorySlug($id)
    {
        switch ($id) {
            case 1:
                return 'maps-bonds';
                break;
            case 4:
                return 'designer-fashion';
                break;
            case 7:
                return 'home-decor';
                break;
            case 9:
                return 'tableware';
                break;
            case 3:
                return 'asian-collectables';
                break;
             case 2:
                return 'art';
                break;
            case 6:
                return 'jewellery';
                break;
            case 10:
                return 'watches';
                break;
            case 12:
                return 'decorative-arts';
                break;
            case 5:
                return 'furniture';
                break;
            case 8:
                return 'rugs-carpets';
                break;
            case 11:
                return 'wine-spirits';
                break;
            default:
                return '';
                break;
        }
    }
}
