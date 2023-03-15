<?php

use Webfox\Xero\OauthCredentialManager;
use App\Modules\Xero\Models\XeroInvoice;

Route::get(
    '/sigin',
    function (OauthCredentialManager $xeroCredentials) {
        try {
            if ($xeroCredentials->exists()) {
                $xero             = resolve(\XeroAPI\XeroPHP\Api\AccountingApi::class);
                $organisationName = $xero->getOrganisations($xeroCredentials->getTenantId())->getOrganisations()[0]->getName();
                $user             = $xeroCredentials->getUser();
                $username         = "{$user['given_name']} {$user['family_name']} ({$user['username']})";
                \Log::channel('xeroLog')->info($username . 'credential success');

                return redirect('/');
            } else {
                return redirect('/xero/auth/authorize');
            }
        } catch (\throwable $e) {
            \Log::channel('xeroLog')->error("Caught Exception ('{$e->getMessage()}')\n{$e}\n");
        }
    }
)->middleware('auth')->name('sigin');

Route::post('/webhook', 'XeroWebhookController');

Route::get('/panel', 'XeroController@panel')->middleware('auth')->name('panel');

Route::get('/panel/datatables', 'XeroController@datatables')->middleware('auth')->name('panel.datatables');

Route::get(
    'make/invoice/{id}',
    function ($id) {
        $xeroInvoice = XeroInvoice::findOrFail($id);

        $type = request()->type ?? 'all';
        if ($type == 'all') {
            $itemIds = XeroInvoice::where('auction_id', $xeroInvoice->auction_id)->where('buyer_id', $xeroInvoice->buyer_id)->where('seller_id', $xeroInvoice->seller_id)->whereIn('status', [ 0, 2, 3 ])->pluck('item_id');
            $customers = 'all';
        } elseif ($type == 'bill') {
            $itemIds = XeroInvoice::where('auction_id', $xeroInvoice->auction_id)->where('buyer_id', $xeroInvoice->buyer_id)->where('seller_id', $xeroInvoice->seller_id)->whereIn('status', [ 0, 2, 3 ])->pluck('item_id');
            $customers = $xeroInvoice->seller_id;
        } elseif ($type == 'invoice') {
            $itemIds = XeroInvoice::where('auction_id', $xeroInvoice->auction_id)->where('buyer_id', $xeroInvoice->buyer_id)->where('seller_id', $xeroInvoice->seller_id)->whereIn('status', [ 0, 2, 3 ])->pluck('item_id');
            $customers = $xeroInvoice->buyer_id;
        }
        $items = implode(",", $itemIds->toArray());

        Artisan::call(
            'generate:invoice',
            [
            '-A' => $xeroInvoice->auction_id ?? 'all',
            '-C' => $customers,
            '-I' => $items,
            '-T' => $type
            ]
        );

        return redirect()->route('xero.panel');
    }
)->middleware('auth')->name('makeinvoice');

Route::get('/account/services', 'XeroController@accountServices')->middleware('auth')->name('account.services');

Route::get('/account/services/{xeroItem}/edit', 'XeroController@accountServicesEdit')->middleware('auth')->name('account.services.edit');

Route::get('/account/services/{xeroItem}/sync', 'XeroController@accountServicesSync')->middleware('auth')->name('account.services.sync');

Route::get('/account/services/{xeroItem}/delete', 'XeroController@accountServicesDelete')->middleware('auth')->name('account.services.delete');

Route::get('/account/services/sync', 'XeroController@accountServicesSyncAll')->middleware('auth')->name('account.services.sync.all');

Route::put('/account/services/{xeroItem}/update', 'XeroController@accountServicesUpdate')->middleware('auth')->name('account.services.update');

Route::get('/account/services/datatables', 'XeroController@accountServicesDatatables')->middleware('auth')->name('account.services.datatables');

Route::get('/tracking/categories', 'XeroController@trackingCategories')->middleware('auth')->name('tracking.categories');

Route::post('/tracking/categories/{xeroTracking}/update', 'XeroController@trackingCategoriesUpdate')->middleware('auth')->name('tracking.categories.update');

Route::get('/publish/invoice/{invoice_id?}/{auction_id?}/{local?}', 'XeroController@publishInvoice')->middleware('auth')->name('publish.invoice');

Route::get('/publish/bill/{invoice_id?}/{auction_id?}', 'XeroController@publishBill')->middleware('auth')->name('publish.bill');

Route::get('/sync-invoice-update', 'XeroController@syncXeroInvoiceUpdate')->middleware('auth')->name('syncXeroInvoiceUpdate');

Route::post('/sync-invoice-update', 'XeroController@syncXeroInvoice')->middleware('auth')->name('syncXeroInvoice');

Route::get('{id}/sync-invoice', 'XeroController@syncInvoice')->middleware('auth')->name('syncInvoice');

Route::get('{id}/genereate-invoice-url', 'XeroController@generateInvoiceUrl')->middleware('auth')->name('genereateInvoiceUrl');

Route::post('{invoice_id}/split-settlement', 'XeroController@splitSettlement')->middleware('auth')->name('splitSettlement');

Route::get('429', 'XeroController@get429')->middleware('auth');

Route::post('{invoice_id}/split-settlement', 'XeroController@splitSettlement')->middleware('auth')->name('splitSettlement');

Route::get('/sync-settlement-update', 'XeroController@syncSettlementUpdate')->middleware('auth')->name('syncSettlementUpdate');

Route::get('/error', 'XeroController@error')->middleware('auth')->name('error');

Route::get('/error/datatables', 'XeroController@errorDatatables')->middleware('auth')->name('error.datatables');

Route::get('/error/delete/{id}', 'XeroController@errorDelete')->middleware('auth')->name('error.delete');

Route::get('/automate-invoice-items', 'XeroController@automateInvoiceItems')->middleware('auth');

Route::post('/check_invoice_items', 'XeroController@checkInvoiceItems')->middleware('auth')->name('check_invoice_items');
