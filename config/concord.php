<?php

use Konekt\AppShell\Assets\DefaultAppShellAssets;

return [
    'modules' => [
        Konekt\AppShell\Providers\ModuleServiceProvider::class => [
            'ui' => [
                'name' => 'HotLotz',
                'url' => '/manage/customers',
                'assets' => [
                    'js'  => ['js/app.js'],
                    'css' => [
                        'css/appshell.css?v1.1',
                        '/material-design-iconic-font/css/material-design-iconic-font.min.css',
                        '/custom/css/pickadate/themes/default.css','/custom/css/pickadate/themes/default.date.css','/custom/css/pickadate/themes/default.time.css',
                    ]
                ]
            ],
            'routes' => [
                [
                    'prefix'     => 'manage',
                    'as'         => 'appshell.',
                    'middleware' => ['web', 'auth', 'acl'],
                    'files'      => ['acl']
                ],
                [
                    'prefix'     => 'manage',
                    'as'         => 'appshell.',
                    'middleware' => ['web', 'auth'],
                    'files'      => ['nonacl']
                ],
            ],
        ],
        Vanilo\Framework\Providers\ModuleServiceProvider::class => [
            'routes' => [
                'prefix'     => 'manage',
                'as'         => 'vanilo.',
                'middleware' => ['web', 'auth', 'acl'],
                'files'      => ['admin']
            ],
        ],
        App\Modules\Customer\Providers\ModuleServiceProvider::class => [
            'routes' => [
                'files'      => ['web', 'breadcrumbs'],
                'prefix'    => 'manage',
                'as'        => 'customer.',
                'middleware' => ['web', 'auth', 'acl'],
            ],
            'migrations' => true,
        ],
        App\Modules\Auction\Providers\ModuleServiceProvider::class => [
            'routes' => [
                'files'      => ['web', 'breadcrumbs'],
                'prefix'    => 'manage',
                'as'        => 'auction.',
                'middleware' => ['web', 'auth', 'acl'],
            ],
            'migrations' => true,
        ],
        App\Modules\Item\Providers\ModuleServiceProvider::class => [
            'routes' => [
                'files'      => ['web', 'breadcrumbs'],
                'prefix'    => 'manage',
                'as'        => 'item.',
                'middleware' => ['web', 'auth', 'acl'],
            ],
            'migrations' => true,
        ],
        App\Modules\Category\Providers\ModuleServiceProvider::class => [
            'routes' => [
                'files'      => ['web', 'breadcrumbs'],
                'prefix'    => 'manage',
                'as'        => 'category.',
                'middleware' => ['web', 'auth', 'acl'],
            ],
            'migrations' => true,
        ],
        App\Modules\Marketing\Providers\ModuleServiceProvider::class => [
            'routes' => [
                'files'      => ['web', 'breadcrumbs'],
                'prefix'    => 'manage',
                'as'        => 'marketing.',
                'middleware' => ['web', 'auth', 'acl'],
            ],
            'migrations' => true,
        ],
        App\Modules\Report\Providers\ModuleServiceProvider::class => [
            'routes' => [
                'files'      => ['web', 'breadcrumbs'],
                'prefix'    => 'manage',
                'as'        => 'report.',
                'middleware' => ['web', 'auth', 'acl'],
            ],
            'migrations' => true,
        ],
        App\Modules\EmailTemplate\Providers\ModuleServiceProvider::class => [
            'routes' => [
                'files'      => ['web', 'breadcrumbs'],
                'prefix'    => 'manage',
                'as'        => 'email_template.',
                'middleware' => ['web', 'auth'],
            ],
            'migrations' => true,
        ],
        App\Modules\Marketplace\Providers\ModuleServiceProvider::class => [
            'routes' => [
                'files'      => ['web', 'breadcrumbs'],
                'prefix'    => 'manage',
                'as'        => 'marketplace.',
                'middleware' => ['web', 'auth'],
            ],
            'migrations' => true,
        ],
        App\Modules\ContentManagement\Providers\ModuleServiceProvider::class => [
            'routes' => [
                'files'      => ['web', 'breadcrumbs'],
                'prefix'    => 'manage',
                'as'        => 'content_management.',
                'middleware' => ['web', 'auth'],
            ],
            'migrations' => true,
        ],
        App\Modules\FaqCategory\Providers\ModuleServiceProvider::class => [
            'routes' => [
                'files'      => ['web', 'breadcrumbs'],
                'prefix'    => 'manage',
                'as'        => 'faq_category.',
                'middleware' => ['web', 'auth'],
            ],
            'migrations' => true,
        ],
        App\Modules\Faq\Providers\ModuleServiceProvider::class => [
            'routes' => [
                'files'      => ['web', 'breadcrumbs'],
                'prefix'    => 'manage',
                'as'        => 'faq.',
                'middleware' => ['web', 'auth'],
            ],
            'migrations' => true,
        ],
        App\Modules\StrategicPartner\Providers\ModuleServiceProvider::class => [
            'routes' => [
                'files'      => ['web', 'breadcrumbs'],
                'prefix'    => 'manage',
                'as'        => 'strategic_partner.',
                'middleware' => ['web', 'auth'],
            ],
            'migrations' => true,
        ],
        App\Modules\ProfessionalValuations\Providers\ModuleServiceProvider::class => [
            'routes' => [
                'files'      => ['web', 'breadcrumbs'],
                'prefix'    => 'manage',
                'as'        => 'professional_valuation.',
                'middleware' => ['web', 'auth'],
            ],
            'migrations' => true,
        ],
        App\Modules\HomePage\Providers\ModuleServiceProvider::class => [
            'routes' => [
                'files'      => ['web', 'breadcrumbs'],
                'prefix'    => 'manage',
                'as'        => 'home_page.',
                'middleware' => ['web', 'auth'],
            ],
            'migrations' => true,
        ],
        App\Modules\Testimonial\Providers\ModuleServiceProvider::class => [
            'routes' => [
                'files'      => ['web', 'breadcrumbs'],
                'prefix'    => 'manage',
                'as'        => 'testimonial.',
                'middleware' => ['web', 'auth'],
            ],
            'migrations' => true,
        ],
        App\Modules\EmailTrigger\Providers\ModuleServiceProvider::class => [
            'routes' => [
                'files'      => ['web', 'breadcrumbs'],
                'prefix'    => 'manage',
                'as'        => 'email_trigger.',
                'middleware' => ['web', 'auth'],
            ],
            'migrations' => true,
        ],
        App\Modules\EmailLog\Providers\ModuleServiceProvider::class => [
            'routes' => [
                'files'      => ['web', 'breadcrumbs'],
                'prefix'    => 'manage',
                'as'        => 'email_log.',
                'middleware' => ['web', 'auth'],
            ]
        ],
        App\Modules\ItemLifecycleTrigger\Providers\ModuleServiceProvider::class => [
            'routes' => [
                'files'      => ['web', 'breadcrumbs'],
                'prefix'    => 'manage',
                'as'        => 'item_lifecycle_trigger.',
                'middleware' => ['web', 'auth'],
            ],
            'migrations' => true,
        ],
        App\Modules\ItemDuplicator\Providers\ModuleServiceProvider::class => [
            'routes' => [
                'files'      => ['web', 'breadcrumbs'],
                'prefix'    => 'manage',
                'as'        => 'item_duplicator.',
                'middleware' => ['web', 'auth'],
            ],
            'migrations' => true,
        ],
        App\Modules\WhatWeSell\Providers\ModuleServiceProvider::class => [
            'routes' => [
                'files'      => ['web', 'breadcrumbs'],
                'prefix'    => 'manage',
                'as'        => 'whatwesell.',
                'middleware' => ['web', 'auth'],
            ],
            'migrations' => true,
        ],
        App\Modules\HowToBuy\Providers\ModuleServiceProvider::class => [
            'routes' => [
                'files'      => ['web', 'breadcrumbs'],
                'prefix'    => 'manage',
                'as'        => 'how_to_buy.',
                'middleware' => ['web', 'auth'],
            ],
            'migrations' => true,
        ],
        App\Modules\HowToSell\Providers\ModuleServiceProvider::class => [
            'routes' => [
                'files'      => ['web', 'breadcrumbs'],
                'prefix'    => 'manage',
                'as'        => 'how_to_sell.',
                'middleware' => ['web', 'auth'],
            ],
            'migrations' => true,
        ],
        App\Modules\AboutUs\Providers\ModuleServiceProvider::class => [
            'routes' => [
                'files'      => ['web', 'breadcrumbs'],
                'prefix'    => 'manage',
                'as'        => 'about_us.',
                'middleware' => ['web', 'auth'],
            ],
            'migrations' => true,
        ],
        App\Modules\SysConfig\Providers\ModuleServiceProvider::class => [
            'routes' => [
                'files'      => ['web', 'breadcrumbs'],
                'prefix'    => 'manage',
                'as'        => 'sys_config.',
                'middleware' => ['web', 'auth'],
            ],
            'migrations' => true,
        ],
        App\Modules\AuctionCms\Providers\ModuleServiceProvider::class => [
            'routes' => [
                'files'      => ['web', 'breadcrumbs'],
                'prefix'    => 'manage',
                'as'        => 'auction_cms.',
                'middleware' => ['web', 'auth'],
            ],
            'migrations' => true,
        ],
        App\Modules\MarketplaceCms\Providers\ModuleServiceProvider::class => [
            'routes' => [
                'files'      => ['web', 'breadcrumbs'],
                'prefix'    => 'manage',
                'as'        => 'marketplace_cms.',
                'middleware' => ['web', 'auth'],
            ],
            'migrations' => true,
        ],
        App\Modules\HomeContent\Providers\ModuleServiceProvider::class => [
            'routes' => [
                'files'      => ['web', 'breadcrumbs'],
                'prefix'    => 'manage',
                'as'        => 'home_content.',
                'middleware' => ['web', 'auth'],
            ],
            'migrations' => true,
        ],
        App\Modules\BusinessSeller\Providers\ModuleServiceProvider::class => [
            'routes' => [
                'files'      => ['web', 'breadcrumbs'],
                'prefix'    => 'manage',
                'as'        => 'business_seller.',
                'middleware' => ['web', 'auth'],
            ],
            'migrations' => true,
        ],
        App\Modules\HotlotzConcierge\Providers\ModuleServiceProvider::class => [
            'routes' => [
                'files'      => ['web', 'breadcrumbs'],
                'prefix'    => 'manage',
                'as'        => 'hotlotz_concierge.',
                'middleware' => ['web', 'auth'],
            ],
            'migrations' => true,
        ],
        App\Modules\ShippingAndStorage\Providers\ModuleServiceProvider::class => [
            'routes' => [
                'files'      => ['web', 'breadcrumbs'],
                'prefix'    => 'manage',
                'as'        => 'shipping_and_storage.',
                'middleware' => ['web', 'auth'],
            ],
            'migrations' => true,
        ],
        App\Modules\PrivateCollections\Providers\ModuleServiceProvider::class => [
            'routes' => [
                'files'      => ['web', 'breadcrumbs'],
                'prefix'    => 'manage',
                'as'        => 'private_collections.',
                'middleware' => ['web', 'auth'],
            ],
            'migrations' => true,
        ],
        App\Modules\Xero\Providers\ModuleServiceProvider::class => [
            'routes' => true,
            'routes' => [
                'files'=> ['web', 'breadcrumbs'],
                'prefix'    => '/xero',
                'as'        => 'xero.',
            ],
             'views' => [
                'namespace' => 'xero'
             ],
            'event_listeners' => true
        ],
        App\Modules\HomePageRandomText\Providers\ModuleServiceProvider::class => [
            'routes' => [
                'files'      => ['web', 'breadcrumbs'],
                'prefix'    => 'manage',
                'as'        => 'home_page_random_text.',
                'middleware' => ['web', 'auth'],
            ],
            'migrations' => true,
        ],
        App\Modules\Stripe\Providers\ModuleServiceProvider::class => [
            'routes' => true,
            'routes' => [
                'files'=> ['web'],
                'prefix'    => '/stripe',
                'as'        => 'stripe.',
                'middleware' => ['web'],
            ],
             'views' => [
                'namespace' => 'stripe'
            ]
        ],
        App\Modules\SellWithUs\Providers\ModuleServiceProvider::class => [
            'routes' => [
                'files'      => ['web', 'breadcrumbs'],
                'prefix'    => 'manage',
                'as'        => 'sell_with_us.',
                'middleware' => ['web', 'auth'],
            ],
            'migrations' => true,
        ],
        App\Modules\Glossary\Providers\ModuleServiceProvider::class => [
            'routes' => [
                'files'      => ['web', 'breadcrumbs'],
                'prefix'    => 'manage',
                'as'        => 'glossary.',
                'middleware' => ['web', 'auth'],
            ],
            'migrations' => true,
        ],
        App\Modules\AuctionMainPage\Providers\ModuleServiceProvider::class => [
            'routes' => [
                'files'      => ['web', 'breadcrumbs'],
                'prefix'    => 'manage',
                'as'        => 'auction_main_page.',
                'middleware' => ['web', 'auth'],
            ],
            'migrations' => true,
        ],
        App\Modules\Policy\Providers\ModuleServiceProvider::class => [
            'routes' => [
                'files'      => ['web', 'breadcrumbs'],
                'prefix'    => 'manage',
                'as'        => 'policy.',
                'middleware' => ['web', 'auth'],
            ],
            'migrations' => true,
        ],
        App\Modules\MarketplaceHomeBanner\Providers\ModuleServiceProvider::class => [
            'routes' => [
                'files'      => ['web', 'breadcrumbs'],
                'prefix'    => 'manage',
                'as'        => 'marketplace_home_banner.',
                'middleware' => ['web', 'auth'],
            ],
            'migrations' => true,
        ],
        App\Modules\MarketplaceHome\Providers\ModuleServiceProvider::class => [
            'routes' => [
                'files'      => ['web', 'breadcrumbs'],
                'prefix'    => 'manage',
                'as'        => 'marketplace_home.',
                'middleware' => ['web', 'auth'],
            ],
            'migrations' => true,
        ],
        App\Modules\LocationCms\Providers\ModuleServiceProvider::class => [
            'routes' => [
                'files'      => ['web', 'breadcrumbs'],
                'prefix'    => 'manage',
                'as'        => 'location_cms.',
                'middleware' => ['web', 'auth'],
            ],
            'migrations' => true,
        ],
        App\Modules\Careers\Providers\ModuleServiceProvider::class => [
            'routes' => [
                'files'      => ['web', 'breadcrumbs'],
                'prefix'    => 'manage',
                'as'        => 'careers.',
                'middleware' => ['web', 'auth'],
            ],
            'migrations' => true,
        ],
        App\Modules\MediaResource\Providers\ModuleServiceProvider::class => [
            'routes' => [
                'files'      => ['web', 'breadcrumbs'],
                'prefix'    => 'manage',
                'as'        => 'media_resource.',
                'middleware' => ['web', 'auth'],
            ],
            'migrations' => true,
        ],
        App\Modules\Recaptcha\Providers\ModuleServiceProvider::class => [
            'views' => [
                'namespace' => 'recaptcha'
            ]
        ],
        App\Modules\OurTeam\Providers\ModuleServiceProvider::class => [
            'routes' => [
                'files'      => ['web', 'breadcrumbs'],
                'prefix'    => 'manage',
                'as'        => 'our_team.',
                'middleware' => ['web', 'auth'],
            ],
            'migrations' => true,
        ],
        App\Modules\InternalAdvert\Providers\ModuleServiceProvider::class => [
            'routes' => [
                'files'      => ['web', 'breadcrumbs'],
                'prefix'    => 'manage',
                'as'        => 'internal_advert.',
                'middleware' => ['web', 'auth'],
            ],
            'migrations' => true,
        ],
        App\Modules\CaseStudy\Providers\ModuleServiceProvider::class => [
            'routes' => [
                'files'      => ['web', 'breadcrumbs'],
                'prefix'    => 'manage',
                'as'        => 'case_study.',
                'middleware' => ['web', 'auth'],
            ],
            'migrations' => true,
        ],
        App\Modules\OrderSummary\Providers\ModuleServiceProvider::class => [
            'routes' => [
                'files'      => ['web', 'breadcrumbs'],
                'prefix'    => 'manage',
                'as'        => 'order_summary.',
                'middleware' => ['web', 'auth'],
            ],
            'migrations' => true,
            'event_listeners' => true
        ],
        App\Modules\MainBanner\Providers\ModuleServiceProvider::class => [
            'routes' => [
                'files'      => ['web', 'breadcrumbs'],
                'prefix'    => 'manage',
                'as'        => 'main_banner.',
                'middleware' => ['web', 'auth'],
            ],
            'migrations' => true,
            'event_listeners' => true
        ],
        App\Modules\MarketplaceBanner\Providers\ModuleServiceProvider::class => [
            'routes' => [
                'files'      => ['web', 'breadcrumbs'],
                'prefix'    => 'manage',
                'as'        => 'marketplace_banner.',
                'middleware' => ['web', 'auth'],
            ],
            'migrations' => true,
            'event_listeners' => true
        ],
        App\Modules\TickerDisplay\Providers\ModuleServiceProvider::class => [
            'routes' => [
                'files'      => ['web', 'breadcrumbs'],
                'prefix'    => 'manage',
                'as'        => 'ticker_display.',
                'middleware' => ['web', 'auth'],
            ],
            'migrations' => true,
            'event_listeners' => true
        ],
        App\Modules\WhatWeSells\Providers\ModuleServiceProvider::class => [
            'routes' => [
                'files'      => ['web', 'breadcrumbs'],
                'prefix'    => 'manage',
                'as'        => 'what_we_sell.',
                'middleware' => ['web', 'auth'],
            ],
            'migrations' => true,
            'event_listeners' => true
        ],
        App\Modules\MarketplaceMainBanner\Providers\ModuleServiceProvider::class => [
            'routes' => [
                'files'      => ['web', 'breadcrumbs'],
                'prefix'    => 'manage',
                'as'        => 'marketplace_main_banner.',
                'middleware' => ['web', 'auth'],
            ],
            'migrations' => true,
            'event_listeners' => true
        ],
        App\Modules\WhatsNewArticleOne\Providers\ModuleServiceProvider::class => [
            'routes' => [
                'files'      => ['web', 'breadcrumbs'],
                'prefix'    => 'manage',
                'as'        => 'whats_new_article_one.',
                'middleware' => ['web', 'auth'],
            ],
            'migrations' => true,
            'event_listeners' => true
        ],
        App\Modules\WhatsNewWelcome\Providers\ModuleServiceProvider::class => [
            'routes' => [
                'files'      => ['web', 'breadcrumbs'],
                'prefix'    => 'manage',
                'as'        => 'whats_new_welcome.',
                'middleware' => ['web', 'auth'],
            ],
            'migrations' => true,
            'event_listeners' => true
        ],
        App\Modules\WhatsNewBidBarometer\Providers\ModuleServiceProvider::class => [
            'routes' => [
                'files'      => ['web', 'breadcrumbs'],
                'prefix'    => 'manage',
                'as'        => 'whats_new_bid_barometer.',
                'middleware' => ['web', 'auth'],
            ],
            'migrations' => true,
            'event_listeners' => true
        ],
        App\Modules\BlogPost\Providers\ModuleServiceProvider::class => [
            'routes' => [
                'files'      => ['web', 'breadcrumbs'],
                'prefix'    => 'manage',
                'as'        => 'blog_post.',
                'middleware' => ['web', 'auth'],
            ],
            'migrations' => true,
            'event_listeners' => true
        ],
        App\Modules\BlogArticle\Providers\ModuleServiceProvider::class => [
            'routes' => [
                'files'      => ['web', 'breadcrumbs'],
                'prefix'    => 'manage',
                'as'        => 'blog_article.',
                'middleware' => ['web', 'auth'],
            ],
            'migrations' => true,
            'event_listeners' => true
        ],
        App\Modules\Support\Providers\ModuleServiceProvider::class => [
            'routes' => [
                'files'      => ['web', 'breadcrumbs'],
                'prefix'    => 'manage',
                'as'        => 'support.',
                'middleware' => ['web', 'auth'],
            ],
            'migrations' => true
        ],
        App\Modules\AdminEmail\Providers\ModuleServiceProvider::class => [
            'routes' => [
                'files'      => ['web', 'breadcrumbs'],
                'prefix'    => 'manage',
                'as'        => 'admin_email.',
            ],
        ],
        App\Modules\DocumentModule\Providers\ModuleServiceProvider::class => [
            'routes' => [
                'files'      => ['web', 'breadcrumbs'],
                'prefix'    => 'manage',
                'as'        => 'document.',
                'middleware' => ['web', 'auth'],
            ],
            'migrations' => true
        ],
        App\Modules\AutomateItem\Providers\ModuleServiceProvider::class => [
            'routes' => [
                'files'      => ['web', 'breadcrumbs'],
                'prefix'    => 'manage',
                'as'        => 'automate_item.',
                'middleware' => ['web', 'auth'],
            ],
            'migrations' => true
        ],
    ]
];
