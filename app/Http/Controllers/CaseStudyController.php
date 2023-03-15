<?php

namespace App\Http\Controllers;

use App\Helpers\MenuHelper;
use App\Modules\SysConfig\Models\SysConfig;
use App\Repositories\CaseStudyRepository;

class CaseStudyController extends Controller
{

    protected $caseStudyRepository;

    public function __construct(
        CaseStudyRepository $caseStudyRepository
    )
    {
        $this->caseStudyRepository = $caseStudyRepository;
    }

    public function detail($slug=""){
        $title = "Singaporean Collection Case Study";
        $highlightTitle = "Singaporean Collection Highlights";

        if($slug == 'singapore'){
            $title = "Singaporean Collection Case Study";
            $highlightTitle = "Singaporean Collection Highlights";
            $highlightItems = $this->highlightCaseStudySingapore();
            $resultRates = $this->resultRateSingapore();
        }
        if($slug == 'qkl'){
            $title = "QKL Case Study";
            $highlightTitle = "QKL Highlights";
            $highlightItems = $this->highlightCaseStudyQkl();
            $resultRates = $this->resultRateQkl();
        }
        if($slug == 'everton'){
            $title = "26 Everton Road Case Study";
            $highlightTitle = "26 Everton Road Highlights";
            $highlightItems = $this->highlightCaseStudyEverton();
            $resultRates = $this->resultRateEverton();
        }

        $today_open_time = SysConfig::getTodayOpenTime();

        $data = compact('title', 'highlightTitle', 'highlightItems', 'resultRates', 'today_open_time');

        $menus = MenuHelper::getCaseStudyMenu();
        $caseStudy = $this->getCaseStudy($slug);

        $data = [
            'menus' => $menus,
            'banner' => $caseStudy['image'],
            'caption' => $caseStudy['caption'],
            'title' => $caseStudy['title'],
            'today_open_time' => $today_open_time,
        ];

        return view('pages.case_study', $data);
    }

    protected function getCaseStudy($slug){
        if($slug == "singapore"){
            return [
                'title' => 'Case Study : Singapore',
                'image' => 'https://s3-ap-southeast-1.amazonaws.com/sample.hotlotz.com/Services/case-study/singaporean-collection/CaseStudy1_banner1.jpg',
                'caption' => 'A PAIR OF STRAITS CHINESE PERANAKAN FAMILLE ROSE KAMCHENG',
            ];
        }
    }

    protected function highlightCaseStudySingapore()
    {
        $marketPlace = collect([
            [
                'photoPath' => 'ecommerce/images/Services/case-study/singaporean-collection/CaseStudy1_highlight_1.jpg',
                'itemTitle' => 'A PAIR OF STRAITS CHINESE PERANAKAN FAMILLE ROSE KAMCHENG',
                'priceStatus' => 'SOLD',
                'price' => "$2,000 SGD",
                'buyerLevel' => "BUYER'S PREMIUM "
            ],
            [
                'photoPath' => 'ecommerce/images/Services/case-study/singaporean-collection/CaseStudy1_highlight_2.jpg',
                'itemTitle' => 'A PAIR OF YELLOW GROUND FAMILLE ROSE VASES',
                'priceStatus' => 'SOLD',
                'price' => '$1,500 SGD',
                'buyerLevel' => 'BUYER’S PREMIUM'
            ],
            [
                'photoPath' => 'ecommerce/images/Services/case-study/singaporean-collection/CaseStudy1_highlight_3.jpg',
                'itemTitle' => "A SET OF THREE 'DAYAZHAI' STYLE FAMILLE ROSE SMALL PLATES",
                'priceStatus' => 'SOLD',
                'price' => '$4,200 SGD',
                'buyerLevel' => 'BUYER’S PREMIUM'
            ],
            [
                'photoPath' => 'ecommerce/images/Services/case-study/singaporean-collection/CaseStudy1_highlight_4.jpg',
                'itemTitle' => 'A NEAR PAIR OF LARGE FAMILLE ROSE AND IRON RED BALUSTER VASES',
                'priceStatus' => 'SOLD',
                'price' => '$8,000 SGD',
                'buyerLevel' => 'BUYER’S PREMIUM'
            ],
            [
                'photoPath' => 'ecommerce/images/Services/case-study/singaporean-collection/CaseStudy1_highlight_5.jpg',
                'itemTitle' => 'A PAIR OF YELLOW GROUND FAMILLE ROSE SAUCERS',
                'priceStatus' => 'SOLD',
                'price' => '$550 SGD',
                'buyerLevel' => 'BUYER’S PREMIUM'
            ],
            [
                'photoPath' => 'ecommerce/images/Services/case-study/singaporean-collection/CaseStudy1_highlight_6.jpg',
                'itemTitle' => 'A SMALL RUBY GROUND FAMILLE ROSE BOWL',
                'priceStatus' => 'SOLD',
                'price' => '$300 SGD',
                'buyerLevel' => 'BUYER’S PREMIUM'
            ],
            [
                'photoPath' => 'ecommerce/images/Services/case-study/singaporean-collection/CaseStudy1_highlight_7.jpg',
                'itemTitle' => "A BLUE SILK 'EIGHT-DRAGON' ROBE, JIFU",
                'priceStatus' => 'SOLD',
                'price' => '$1,700 SGD',
                'buyerLevel' => 'BUYER’S PREMIUM'
            ],
            [
                'photoPath' => 'ecommerce/images/Services/case-study/singaporean-collection/CaseStudy1_highlight_8.jpg',
                'itemTitle' => 'A BLUE MONOCHROME PORCELAIN BOTTLE VASE',
                'priceStatus' => 'SOLD',
                'price' => '$350 SGD',
                'buyerLevel' => 'BUYER’S PREMIUM'
            ],
            [
                'photoPath' => 'ecommerce/images/Services/case-study/singaporean-collection/CaseStudy1_highlight_9.jpg',
                'itemTitle' => "A 'SANG DE BOEUF' GLOBULAR PORCELAIN VASE",
                'priceStatus' => 'SOLD',
                'price' => '$650 SGD',
                'buyerLevel' => 'BUYER’S PREMIUM'
            ],
            [
                'photoPath' => 'ecommerce/images/Services/case-study/singaporean-collection/CaseStudy1_highlight_10.jpg',
                'itemTitle' => 'AN INCISED LONGQUAN CELADON LOTUS BOWL',
                'priceStatus' => 'SOLD',
                'price' => '$550 SGD',
                'buyerLevel' => 'BUYER’S PREMIUM'
            ],
            [
                'photoPath' => 'ecommerce/images/Services/case-study/singaporean-collection/CaseStudy1_highlight_11.jpg',
                'itemTitle' => "A PAIR OF CLOISONNÉ ENAMEL DRAGON EWERS",
                'priceStatus' => 'SOLD',
                'price' => '$1,000 SGD',
                'buyerLevel' => 'BUYER’S PREMIUM'
            ],
            [
                'photoPath' => 'ecommerce/images/Services/case-study/singaporean-collection/CaseStudy1_highlight_12.jpg',
                'itemTitle' => "A 'DAYAZHAI' FAMILLE ROSE TEA BOWL AND COVER",
                'priceStatus' => 'SOLD',
                'price' => '$5,500 SGD',
                'buyerLevel' => 'BUYER’S PREMIUM'
            ]
        ]);

        return $marketPlace;
    }

    protected function highlightCaseStudyQkl()
    {
        $marketPlace = collect([
            [
                'photoPath' => 'ecommerce/images/Services/case-study/qkl/CaseStudy1_highlight_1.png',
                'itemTitle' => "A PAIR OF LARGE 'FAMILLE ROSE' PORCELAIN JARDINIERES",
                'priceStatus' => 'SOLD',
                'price' => "$5,500 SGD",
                'buyerLevel' => "BUYER'S PREMIUM "
            ],
            [
                'photoPath' => 'ecommerce/images/Services/case-study/qkl/CaseStudy1_highlight_2.png',
                'itemTitle' => "A 'FAMILLE ROSE' BAT AND PEACH DISH",
                'priceStatus' => 'SOLD',
                'price' => '$900 SGD',
                'buyerLevel' => 'BUYER’S PREMIUM'
            ],
            [
                'photoPath' => 'ecommerce/images/Services/case-study/qkl/CaseStudy1_highlight_3.png',
                'itemTitle' => 'A PAIR OF LARGE IRON RED BALUSTER VASES',
                'priceStatus' => 'SOLD',
                'price' => '$2,200 SGD',
                'buyerLevel' => 'BUYER’S PREMIUM'
            ],
            [
                'photoPath' => 'ecommerce/images/Services/case-study/qkl/CaseStudy1_highlight_4.png',
                'itemTitle' => "A YELLOW GLAZED RECTANGULAR 'JARDINIERE' OR NARCISSUS POT",
                'priceStatus' => 'SOLD',
                'price' => '$900 SGD',
                'buyerLevel' => 'BUYER’S PREMIUM'
            ],
            [
                'photoPath' => 'ecommerce/images/Services/case-study/qkl/CaseStudy1_highlight_5.png',
                'itemTitle' => 'A LARGE PAINTED POTTERY FIGURE OF A COURT LADY WITH A DOG',
                'priceStatus' => 'SOLD',
                'price' => '$4,000 SGD',
                'buyerLevel' => 'BUYER’S PREMIUM'
            ],
            [
                'photoPath' => 'ecommerce/images/Services/case-study/qkl/CaseStudy1_highlight_6.png',
                'itemTitle' => 'A DOUCAI TWIN HANDLED LOTUS AND BAT VASE',
                'priceStatus' => 'SOLD',
                'price' => '$10,500 SGD',
                'buyerLevel' => 'BUYER’S PREMIUM'
            ],
            [
                'photoPath' => 'ecommerce/images/Services/case-study/qkl/CaseStudy1_highlight_7.png',
                'itemTitle' => 'A TALL FLAMBÉ GLAZED MEIPING VASE',
                'priceStatus' => 'SOLD',
                'price' => '$3,000 SGD',
                'buyerLevel' => 'BUYER’S PREMIUM'
            ],
            [
                'photoPath' => 'ecommerce/images/Services/case-study/qkl/CaseStudy1_highlight_8.png',
                'itemTitle' => 'A JUN WARE CIRCULAR SHALLOW DISH',
                'priceStatus' => 'SOLD',
                'price' => '$280 SGD',
                'buyerLevel' => 'BUYER’S PREMIUM'
            ],
            [
                'photoPath' => 'ecommerce/images/Services/case-study/qkl/CaseStudy1_highlight_9.png',
                'itemTitle' => 'AN AMBER GLAZED SHALLOW DISH',
                'priceStatus' => 'SOLD',
                'price' => '$300 SGD',
                'buyerLevel' => 'BUYER’S PREMIUM'
            ],
            [
                'photoPath' => 'ecommerce/images/Services/case-study/qkl/CaseStudy1_highlight_10.png',
                'itemTitle' => "A FINE 'GOLDEN' CELADON TWIN FISH DISH",
                'priceStatus' => 'SOLD',
                'price' => '$3,600 SGD',
                'buyerLevel' => 'BUYER’S PREMIUM'
            ],
            [
                'photoPath' => 'ecommerce/images/Services/case-study/qkl/CaseStudy1_highlight_11.png',
                'itemTitle' => "A LARGE BLUE AND WHITE PORCELAIN 'BAJIXIANG' MOON FLASK",
                'priceStatus' => 'SOLD',
                'price' => '$20,000 SGD',
                'buyerLevel' => 'BUYER’S PREMIUM'
            ],
            [
                'photoPath' => 'ecommerce/images/Services/case-study/qkl/CaseStudy1_highlight_12.png',
                'itemTitle' => 'A PARCEL-GILT BRONZE FIGURE OF BUDDHA',
                'priceStatus' => 'SOLD',
                'price' => '$1,100 SGD',
                'buyerLevel' => 'BUYER’S PREMIUM'
            ]
        ]);

        return $marketPlace;
    }

    protected function highlightCaseStudyEverton()
    {
        $marketPlace = collect([
            [
                'photoPath' => 'ecommerce/images/Services/case-study/everton-road/CaseStudy2_highlight_1.png',
                'itemTitle' => "A TIBETAN 'GESSO' PAINTED ELM CABINET",
                'priceStatus' => 'SOLD',
                'price' => "$1,200 SGD",
                'buyerLevel' => "BUYER'S PREMIUM "
            ],
            [
                'photoPath' => 'ecommerce/images/Services/case-study/everton-road/CaseStudy2_highlight_2.png',
                'itemTitle' => 'A MAHJONG GAME, IN A RED LACQUER BOX',
                'priceStatus' => 'SOLD',
                'price' => '$1,200 SGD',
                'buyerLevel' => 'BUYER’S PREMIUM'
            ],
            [
                'photoPath' => 'ecommerce/images/Services/case-study/everton-road/CaseStudy2_highlight_3.png',
                'itemTitle' => 'A WOODEN SCULPTURE OF GANESHA',
                'priceStatus' => 'SOLD',
                'price' => '$240 SGD',
                'buyerLevel' => 'BUYER’S PREMIUM'
            ],
            [
                'photoPath' => 'ecommerce/images/Services/case-study/everton-road/CaseStudy2_highlight_4.png',
                'itemTitle' => 'A VINTAGE BAKELITE TELEPHONE',
                'priceStatus' => 'SOLD',
                'price' => '$260 SGD',
                'buyerLevel' => 'BUYER’S PREMIUM'
            ],
            [
                'photoPath' => 'ecommerce/images/Services/case-study/everton-road/CaseStudy2_highlight_5.png',
                'itemTitle' => 'A SET OF THREE VIETNAMESE WOODEN LADIES',
                'priceStatus' => 'SOLD',
                'price' => '$160 SGD',
                'buyerLevel' => 'BUYER’S PREMIUM'
            ],
            [
                'photoPath' => 'ecommerce/images/Services/case-study/everton-road/CaseStudy2_highlight_6.png',
                'itemTitle' => 'I PRAPHAN - TWO MONKS TAKING OFFERINGS',
                'priceStatus' => 'SOLD',
                'price' => '$3,200 SGD',
                'buyerLevel' => 'BUYER’S PREMIUM'
            ],
            [
                'photoPath' => 'ecommerce/images/Services/case-study/everton-road/CaseStudy2_highlight_7.png',
                'itemTitle' => "A 'PACIFIC BIRD' PORCELAIN VASE",
                'priceStatus' => 'SOLD',
                'price' => '$550 SGD',
                'buyerLevel' => 'BUYER’S PREMIUM'
            ],
            [
                'photoPath' => 'ecommerce/images/Services/case-study/everton-road/CaseStudy2_highlight_8.png',
                'itemTitle' => 'A PAIR OF LARGE GLOBULAR PORCELAIN VASES',
                'priceStatus' => 'SOLD',
                'price' => '$320 SGD',
                'buyerLevel' => 'BUYER’S PREMIUM'
            ],
            [
                'photoPath' => 'ecommerce/images/Services/case-study/everton-road/CaseStudy2_highlight_9.png',
                'itemTitle' => 'A PAIR OF ART DECO STATUES ON MARBLE BASE',
                'priceStatus' => 'SOLD',
                'price' => '$320 SGD',
                'buyerLevel' => 'BUYER’S PREMIUM'
            ],
            [
                'photoPath' => 'ecommerce/images/Services/case-study/everton-road/CaseStudy2_highlight_10.png',
                'itemTitle' => 'LIULIGONGFANG - COME JOYOUS FORTUNE (GOOD LUCK)',
                'priceStatus' => 'SOLD',
                'price' => '$100 SGD',
                'buyerLevel' => 'BUYER’S PREMIUM'
            ],
            [
                'photoPath' => 'ecommerce/images/Services/case-study/everton-road/CaseStudy2_highlight_11.png',
                'itemTitle' => "JOHN ERDOS - A VINTAGE BRASS DESK LAMP",
                'priceStatus' => 'SOLD',
                'price' => '$180 SGD',
                'buyerLevel' => 'BUYER’S PREMIUM'
            ],
            [
                'photoPath' => 'ecommerce/images/Services/case-study/everton-road/CaseStudy2_highlight_12.png',
                'itemTitle' => 'AN INDONESIAN WOODEN SCULPTURE OF GARUDA BIRD',
                'priceStatus' => 'SOLD',
                'price' => '$240 SGD',
                'buyerLevel' => 'BUYER’S PREMIUM'
            ]
        ]);

        return $marketPlace;
    }

    protected function resultRateQkl()
    {
        $rate = collect([
            [
                'name' => 'Lot Sold',
                'rate' => '223'
            ],
            [
                'name' => 'Registered Bidders',
                'rate' => '156'
            ],
            [
                'name' => 'Hammer Value (SGD)',
                'rate' => '303070'
            ],
            [
                'name' => 'Number of Bids',
                'rate' => '2326'
            ]
        ]);
        return $rate;
    }

    protected function resultRateEverton()
    {
        $rate = collect([
            [
                'name' => 'Lot Sold',
                'rate' => '90%'
            ],
            [
                'name' => 'Registered Bidders',
                'rate' => '135'
            ],
            [
                'name' => 'Hammer Value (SGD)',
                'rate' => '59750'
            ],
            [
                'name' => 'Number of Bids',
                'rate' => '314'
            ]
        ]);
        return $rate;
    }

    // Case Study Result Rates
    protected function resultRateSingapore()
    {
        $rate = collect([
            [
                'name' => 'Lot Sold',
                'rate' => '76%'
            ],
            [
                'name' => 'Registered Bidders',
                'rate' => '86'
            ],
            [
                'name' => 'Hammer Value (SGD)',
                'rate' => '91130'
            ],
            [
                'name' => 'Number of Bids',
                'rate' => '861'
            ]
        ]);
        return $rate;
    }
}
