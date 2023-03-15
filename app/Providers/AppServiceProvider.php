<?php

namespace App\Providers;

use App\Modules\Item\Models\Item;
use Konekt\Gears\Facades\Settings;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Redis;
use App\Modules\Item\Models\ItemImage;
use Illuminate\Queue\Events\JobFailed;
use App\Modules\Auction\Models\Auction;
use Illuminate\Support\ServiceProvider;
use App\Modules\Customer\Models\Customer;
use App\Modules\Item\Observers\ItemObserver;
use App\Modules\Item\Observers\ItemImageObserver;
use App\Modules\Auction\Observers\AuctionObserver;
use App\Modules\Customer\Observers\CustomerObserver;
use App\Modules\Xero\Repositories\XeroInvoiceRepository;
use Konekt\Customer\Contracts\Customer as CustomerContract;
use Konekt\Customer\Contracts\CustomerType as CustomerTypeContract;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // allowIfUserCan('list product types')
        // if($this->app->environment('production')) {
        //     \URL::forceScheme('https');
        // }
        if (config('app.env') !== 'local') {
            $this->app['request']->server->set('HTTPS', true);
        }

        $this->app->concord->registerModel(\Konekt\User\Contracts\User::class, \App\User::class);
        $this->app->concord->registerEnum(
            CustomerTypeContract::class,
            \App\Modules\Customer\Models\CustomerType::class
        );
        $this->app->concord->registerModel(
            CustomerContract::class,
            \App\Modules\Customer\Models\Customer::class
        );

        ## Queue Failed Jobs
        Queue::failing(function (JobFailed $event) {
            // $event->connectionName
            // $event->job
            // $event->exception
            \Log::error('JobFailed : ' . json_encode(['connectionName' => $event->connectionName, 'job' => $event->job, 'exception' => $event->exception]));
        });

        Customer::observe(CustomerObserver::class);
        Item::observe(ItemObserver::class);
        ItemImage::observe(ItemImageObserver::class);
        Auction::observe(AuctionObserver::class);

        $settingsRegistry = $this->app['gears.settings_registry'];
        $settingsRegistry->addByKey('services.stripe.key');
        if (Settings::get('services.stripe.key') == null) {
            Settings::set('services.stripe.key', config('services.stripe.key'));
        }

        $settingsRegistry->addByKey('services.stripe.secret');
        if (Settings::get('services.stripe.secret') == null) {
            Settings::set('services.stripe.secret', config('services.stripe.secret'));
        }

        $settingsRegistry->addByKey('services.recaptcha.on_live');
        if (Settings::get('services.recaptcha.on_live') == null) {
            Settings::set('services.recaptcha.on_live', config('services.recaptcha.on_live'));
        }

        $settingsRegistry->addByKey('services.recaptcha.v3.site_key');
        if (Settings::get('services.recaptcha.v3.site_key') == null) {
            Settings::set('services.recaptcha.v3.site_key', config('services.recaptcha.v3.site_key'));
        }

        $settingsRegistry->addByKey('services.recaptcha.v3.secret_key');
        if (Settings::get('services.recaptcha.v3.secret_key') == null) {
            Settings::set('services.recaptcha.v3.secret_key', config('services.recaptcha.v3.secret_key'));
        }

        $settingsRegistry->addByKey('services.recaptcha.score');
        if (Settings::get('services.recaptcha.score') == null) {
            Settings::set('services.recaptcha.score', config('services.recaptcha.score'));
        }

        $settingsRegistry->addByKey('services.mailchimp.api');
        if (Settings::get('services.mailchimp.api') == null) {
            Settings::set('services.mailchimp.api', config('services.mailchimp.api'));
        }

        $settingsRegistry->addByKey('services.mailchimp.server_prefix');
        if (Settings::get('services.mailchimp.server_prefix') == null) {
            Settings::set('services.mailchimp.server_prefix', config('services.mailchimp.server_prefix'));
        }

        $settingsRegistry->addByKey('services.mailchimp.list_id');
        if (Settings::get('services.mailchimp.list_id') == null) {
            Settings::set('services.mailchimp.list_id', config('services.mailchimp.list_id'));
        }

        $settingsTreeBuilder = $this->app['appshell.settings_tree_builder'];

        $settingsTreeBuilder
            ->addRootNode('serviceTab', __('Third Party Services'))
            ->addChildNode('serviceTab', 'stripe_service_tab', __('Stripe Service'))
            ->addChildNode('serviceTab', 'recaptcha_service_tab', __('Recaptcha Service'))
            ->addChildNode('serviceTab', 'mailchimp_service_tab', __('Mailchimp Service'));

        $settingsTreeBuilder->addSettingItem('stripe_service_tab', ['text', ['label' => __('Key')]], 'services.stripe.key');
        $settingsTreeBuilder->addSettingItem('stripe_service_tab', ['text', ['label' => __('Secret')]], 'services.stripe.secret');

        $settingsTreeBuilder->addSettingItem('recaptcha_service_tab', ['checkbox', ['label' => __('Active')]], 'services.recaptcha.on_live');
        $settingsTreeBuilder->addSettingItem('recaptcha_service_tab', ['text', ['label' => __('Score')]], 'services.recaptcha.score');
        $settingsTreeBuilder->addSettingItem('recaptcha_service_tab', ['text', ['label' => __('V3 Site Key')]], 'services.recaptcha.v3.site_key');
        $settingsTreeBuilder->addSettingItem('recaptcha_service_tab', ['text', ['label' => __('V3 Secret Key')]], 'services.recaptcha.v3.secret_key');

        $settingsTreeBuilder->addSettingItem('mailchimp_service_tab', ['text', ['label' => __('API Key')]], 'services.mailchimp.api');
        $settingsTreeBuilder->addSettingItem('mailchimp_service_tab', ['text', ['label' => __('Server Prefix (Sub domain on mailchimp)')]], 'services.mailchimp.server_prefix');
        $settingsTreeBuilder->addSettingItem('mailchimp_service_tab', ['text', ['label' => __('List ID (Audience ID - https://mailchimp.com/help/find-audience-id)')]], 'services.mailchimp.list_id');

        View::share('mailingLists', array('Marketplace Updates', 'Events', 'Consignment & Valuation', 'Hotlotz Quarterly Newsletter'));

        $redis = Redis::connection();
        $invoices = json_decode($redis->get(':webhook:invoices'));

        if($invoices == null){
            $webhook_invoices = 0;
        }else{
            $invoices = array_unique($invoices);
            $webhook_invoices = sizeof($invoices);
        }
        $settlementSyncDate = $redis->get(':settlement:settlement_sync_date');

        if ($settlementSyncDate == null) {
            $settlementSyncDate = date('Y-m-d');
        }

        View::share('webhook_invoices', $webhook_invoices);

        View::share('settlementSyncDate', $settlementSyncDate);

    }
}
