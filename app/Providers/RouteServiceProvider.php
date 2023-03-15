<?php
namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
use Menu;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //
        parent::boot();
        $this->addMenuItems();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapAdminRoutes();

        $this->mapWebRoutes();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */

    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }

    protected function mapAdminRoutes()
    {
        Route::domain($this->baseDomain('admin'))
            ->middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/admin.php'));
    }

    protected function mapApiRoutes()
    {
        Route::domain($this->baseDomain('api'))
            ->name('api.')
            ->middleware('api')
            ->namespace($this->namespace . '\API')
            ->group(base_path('routes/api.php'));
    }

    private function baseDomain(string $subdomain = ''):
        string
        {
            if (strlen($subdomain) > 0)
            {
                $subdomain = "{$subdomain}.";
            }

            return $subdomain . config('app.base_domain');
        }

        protected function addMenuItems()
        {
            if ($menu = Menu::get('appshell'))
            {
                $menu->removeItem('crm_group');
                $customerGroup = $menu->addItem('customer', __('Client'));
                $customerGroup->addSubItem('customers', __('ALL CLIENTS') , ['route' => 'customer.customers.index'])
                    ->data('icon', 'accounts')
                    ->allowIfUserCan('list customers');
                $customerGroup->addSubItem('add_customers', __('ADD A NEW CLIENT') , ['route' => 'customer.customers.create'])
                    ->data('icon', 'account-add')
                    ->allowIfUserCan('create customers');

                $itemGroup = $menu->addItem('item', __('Item'));
                $itemGroup->addSubItem('items', __('ALL ITEMS') , ['route' => 'item.items.index'])
                    ->data('icon', 'collection-item')
                    ->allowIfUserCan('list items');
                $itemGroup->addSubItem('add_item', __('ADD A NEW ITEM') , ['url' => '/manage/items/add_item_from_item'])
                    ->data('icon', 'collection-item-1')
                    ->allowIfUserCan('create items');

                //Remove Categorization Route
                $menu->removeItem('categories');
                $itemGroup->addSubItem('categories', __('ALL CATEGORIES') , ['route' => 'category.categories.index'])
                    ->data('icon', 'collection-text')
                    ->allowIfUserCan('list categories');

                $auctionGroup = $menu->addItem('auction', __('Auction'));
                $auctionGroup->addSubItem('auctions', __('CURRENT AUCTIONS') , ['route' => 'auction.auctions.index'])
                    ->data('icon', 'square-o')
                    ->allowIfUserCan('list auctions');

                $auctionGroup->addSubItem('auction_closed', __('PAST AUCTIONS') , ['url' => 'manage/auctions?closed=yes', ])
                    ->data('icon', 'trending-down')
                    ->allowIfUserCan('list auctions');
                $auctionGroup->addSubItem('add_auction', __('ADD A NEW AUCTION') , ['route' => 'auction.auctions.create'])
                    ->data('icon', 'plus-square')
                    ->allowIfUserCan('create auctions');
                $auctionGroup->addSubItem('auction_order_summary_list', __('ORDER SUMMARY LIST') , ['url' => '/manage/auction/order-summaries'])
                    ->data('icon', 'assignment')
                    ->allowIfUserCan('manage order summary');;

                $marketplaceGroup = $menu->addItem('marketplace', __('Marketplace'));
                $marketplaceGroup->addSubItem('marketplace_all', __('MARKETPLACE ALL ITEMS') , ['url' => '/manage/marketplaces/marketplace_all'])
                    ->data('icon', 'shopping-cart-plus');
                $marketplaceGroup->addSubItem('new_additions', __('NEW ADDITIONS') , ['url' => '/manage/marketplaces/new_additions'])
                    ->data('icon', 'shopping-cart-plus');
                // ->allowIfUserCan('list new marketplaces');
                $marketplaceGroup->addSubItem('sold_items', __('SOLD') , ['url' => '/manage/marketplaces/sold_items'])
                    ->data('icon', 'shopping-cart-plus');
                //->allowIfUserCan('list sold marketplaces');
                $marketplaceGroup->addSubItem('marketplace_order_summary_list', __('ORDER SUMMARY LIST') , ['url' => '/manage/marketplace/order-summaries'])
                    ->data('icon', 'assignment')
                    ->allowIfUserCan('manage order summary');;

                $content_managementGroup = $menu->addItem('content_management', __('Content Management'));
                $content_managementGroup->addSubItem('testinmonials', __('Testimonial') , ['route' => 'testimonial.testimonials.index'])
                    ->data('icon', 'file-text')
                    ->allowIfUserCan('list content managements');
                $content_managementGroup->addSubItem('home_pages', __('Home Page') , ['route' => 'home_page.home_pages.index'])
                    ->data('icon', 'file-text')
                    ->allowIfUserCan('list content managements');
                $content_managementGroup->addSubItem('termsandconditions', __('Footer Menu') , ['route' => 'content_management.termsandconditions.index'])
                    ->data('icon', 'file-text')
                    ->allowIfUserCan('list content managements');
                $content_managementGroup->addSubItem('professional_valuations', __('Services') , ['route' => 'professional_valuation.professional_valuations.index'])
                    ->data('icon', 'file-text')
                    ->allowIfUserCan('list content managements');
                $content_managementGroup->addSubItem('faqcategories', __('Discover') , ['route' => 'faq_category.faqcategories.index'])
                    ->data('icon', 'file-text')
                    ->allowIfUserCan('list content managements');
                $content_managementGroup->addSubItem('internalAdvert', __('Internal Advert') , ['route' => 'internal_advert.internal_advert.index'])
                    ->data('icon', 'file-text')
                    ->allowIfUserCan('list content managements');
                $content_managementGroup->addSubItem('caseStudy', __('Case Study') , ['route' => 'case_study.case_study.index'])
                    ->data('icon', 'file-text')
                    ->allowIfUserCan('list content managements');
                $content_managementGroup->addSubItem('sellwithus', __('Header') , ['route' => 'sell_with_us.sell_with_uss.index'])
                    ->data('icon', 'file-text')
                    ->allowIfUserCan('list content managements');
                $content_managementGroup->addSubItem('marketplacehome', __('Marketplace') , ['route' => 'marketplace_home.marketplace_homes.index'])
                    ->data('icon', 'file-text')
                    ->allowIfUserCan('list content managements');

                $emailGroup = $menu->addItem('email', __('Email Management'));
                $emailGroup->addSubItem('email_templates', __('Email Templates') , ['route' => 'email_template.email_templates.index'])
                    ->data('icon', 'email')
                    ->allowIfUserCan('list email templates');

                $emailGroup->addSubItem('email_logs', __('Email Logs') , ['url' => 'manage/email_logs'])
                    ->data('icon', 'account-box-mail');
                    
                $emailGroup->addSubItem('admin_emails', __('Admin Emails') , ['route' => 'admin_email.admin_emails.index'])
                    ->data('icon', 'account-box-mail');

                $reportGroup = $menu->addItem('report', __('Report'));
                $reportGroup->addSubItem('reports', __('ALL REPORTS') , ['route' => 'report.reports.index'])
                    ->data('icon', 'view-dashboard')
                    ->allowIfUserCan('list reports');

                $reportGroup->addSubItem('one_tree_planted_report', __('One Tree Planted Report') , ['url' => 'manage/reports/one_tree_planted_report'])
                    ->data('icon', 'collection-text')
                    ->allowIfUserCan('list reports');

                $reportGroup->addSubItem('pspm_report', __('Precious Stone, Precious Metal Report') , ['url' => 'manage/reports/pspm_report'])
                    ->data('icon', 'collection-text')
                    ->allowIfUserCan('list reports');

                $marketingGroup = $menu->addItem('marketing', __('Marketing'));
                $marketingGroup->addSubItem('marketings', __('Marketings') , ['route' => 'marketing.marketings.index'])
                    ->data('icon', 'shopping-cart-plus')
                    ->allowIfUserCan('list marketings');

                $documentGroup = $menu->addItem('document', __('Documents'));
                $documentGroup->addSubItem('policies', __('Policies'), ['url' => 'manage/documents/policies'])
                    ->data('icon', 'folder-star');
                $documentGroup->addSubItem('explainers', __('Explainers'), ['url' => 'manage/documents/explainer'])
                    ->data('icon', 'folder-star');
                $documentGroup->addSubItem('forms', __('Forms'), ['url' => 'manage/documents/form'])
                    ->data('icon', 'folder-star');
                $documentGroup->addSubItem('sops', __('SOPs'), ['url' => 'manage/documents/sop'])
                    ->data('icon', 'folder-star');
                $documentGroup->addSubItem('rosters', __('Rosters'), ['url' => 'manage/documents/roster'])
                    ->data('icon', 'folder-star');

                if (config('app.debug') == true)
                {
                    $developerOptionGroup = $menu->addItem('developer_option', __('Developer Option'));

                    $developerOptionGroup->addSubItem('emailtriggers', __('Email Triggers') , ['route' => 'email_trigger.emailtriggers.index'])
                        ->data('icon', 'account-box-mail')
                        ->allowIfUserCan('list email triggers');

                    $developerOptionGroup->addSubItem('itemlifecycletriggers', __('Item Lifecycle Triggers') , ['route' => 'item_lifecycle_trigger.itemlifecycletriggers.index'])
                        ->data('icon', 'refresh-sync-alert')
                        ->allowIfUserCan('list item lifecycle triggers');

                    $developerOptionGroup->addSubItem('itemDuplicator', __('Item Duplicator') , ['route' => 'item_duplicator.item_duplicator.index'])
                        ->data('icon', 'copy')
                        ->allowIfUserCan('list item duplicators');

                    $developerOptionGroup->addSubItem('syncXeroUpdateInvoice', __('Sync Invoice Update') , ['url' => '/xero/sync-invoice-update'])
                        ->data('icon', 'refresh-sync');

                    $developerOptionGroup->addSubItem('support', __('VERIFY CUSTOMER EMAIL') , ['url' => '/manage/support'])
                        ->data('icon', 'account-box-mail')
                        ->allowIfUserCan('list support');

                    $developerOptionGroup->addSubItem('checkAuctionQueue', __('Check auction queue'), ['url' => '/internal/check-auction-queue'])
                        ->data('icon', 'hourglass-alt');

                    $developerOptionGroup->addSubItem('automateXeroInvoce', __('Automate xero invoice items'), ['url' => '/xero/automate-invoice-items'])
                        ->data('icon', 'receipt');

                    $developerOptionGroup->addSubItem('automate_items', __('Automate Items'), ['route' => 'automate_item.automate_items.index'])
                        ->data('icon', 'receipt');
                }

                $sysConfigGroup = $menu->addItem('sys_config', __('System Configuration'));
                $sysConfigGroup->addSubItem('sys_configs', __('System Configurations') , ['route' => 'sys_config.sys_configs.index'])
                    ->data('icon', 'settings-square')
                    ->allowIfUserCan('list sys configs');

                $xeroGroup = $menu->addItem('xero', __('FINANCE'));
                $xeroGroup->addSubItem('xero_account_services', __('Account Services') , ['url' => '/xero/account/services'])
                    ->data('icon', 'collection-text')
                    ->allowIfUserCan('list xeros');
                $xeroGroup->addSubItem('xero_tracking_categories', __('Tracking categories') , ['url' => '/xero/tracking/categories'])
                    ->data('icon', 'trending-up')
                    ->allowIfUserCan('list xeros');
                $xeroGroup->addSubItem('xero_pending_list', __('Pending Invoice List') , ['url' => '/xero/panel'])
                    ->data('icon', 'stop')
                    ->allowIfUserCan('list xeros');
                $xeroGroup->addSubItem('xero_error_list', __('Xero Error List'), ['url' => '/xero/error'])
                    ->data('icon', 'alert-polygon')
                    ->allowIfUserCan('list xeros');
                $xeroGroup->addSubItem('third_party_payment', __('Third Party Alert'), ['url' => '/third-party-payment-alert'])
                    ->data('icon', 'alert-triangle')
                    ->allowIfUserCan('list xeros');

                $menu->removeItem('shop');
            }

            $setting = array();
            foreach ($menu->items as $key => $value)
            {
                if ($key == 'settings_group')
                {
                    $setting = $value;
                }
            }
            unset($menu->items['settings_group']);
            $menu->items['settings_group'] = $setting;

            $menu->removeItem('channels');
            $menu->removeItem('roles');

            $menu
                ->settings_group
                ->addSubItem('roles', __('Role Permissions') , ['route' => 'appshell.role.index'])
                ->data('icon', 'shield-security')
                ->allowIfUserCan('list roles');
        }
    }
