<?php

namespace App\Modules\Xero\Accounting;

use Storage;
use App\Helpers\StorageHelper;
use XeroAPI\XeroPHP\Api\AccountingApi;
use Webfox\Xero\OauthCredentialManager;

class Automate
{
    public $apiInstance;

    public function __construct(
        OauthCredentialManager $xeroCredentials,
        AccountingApi $apiInstance
    ) {
        $this->xeroCredentials = $xeroCredentials;
        $this->apiInstance = $apiInstance;
    }

    protected function xeroTenantId()
    {
        return $this->xeroCredentials->getTenantId();
    }

    /**
     * Get invoice
     *
     * @return \XeroAPI\XeroPHP\Models\Accounting\Invoice
     */
    public function getInvoice($invoice_id)
    {
        \Log::channel('xeroLog')->info('Get Xero invoice function work');
        try {
            $result = $this->apiInstance->getInvoice($this->xeroTenantId(), $invoice_id);

            return $result->getInvoices()[0];
        } catch (\throwable $e) {
            \Log::channel('xeroLog')->error("Caught Exception ('{$e->getMessage()}')\n{$e}\n");
            return null;
        }
    }

    public function getInvoiceUrl($invoice_id)
    {
        \Log::channel('xeroLog')->info('Get Xero invoice pdf function work');
        try {
            $result = $this->apiInstance->getOnlineInvoice($this->xeroTenantId(), $invoice_id);

            return $result->getOnlineInvoices()[0]->getOnlineInvoiceUrl();
        } catch (\throwable $e) {
            \Log::channel('xeroLog')->error("Caught Exception ('{$e->getMessage()}')\n{$e}\n");

            return null;
        }
    }

    public function getInvoiceAsPdf($invoice_id)
    {
        \Log::channel('xeroLog')->info('Get Xero invoice pdf function work');
        try {
            $result = $this->apiInstance->getInvoiceAsPdf($this->xeroTenantId(), $invoice_id, "application/pdf");

            // read PDF contents
            $content = $result->fread($result->getSize());

            $path = 'xero/pdf/' . $invoice_id;
            Storage::deleteDirectory($path);
            $filePath = 'xero/pdf/' . $invoice_id . '/' . date('Y-m-d') . '.pdf';
            Storage::put($filePath, $content);
            $data = StorageHelper::get($path);

            return $data[0]['data'];
        } catch (\throwable $e) {
            \Log::channel('xeroLog')->error("Caught Exception ('{$e->getMessage()}')\n{$e}\n");

            return null;
        }
    }

    public function getItem($code)
    {
        \Log::channel('xeroLog')->info('Get Xero item function work');
        $where = 'Code=="' . $code . '"';
        try {
            $result = $this->apiInstance->getItems($this->xeroTenantId(), null, $where);

            return $result->getItems()[0];
        } catch (\throwable $e) {
            \Log::channel('xeroLog')->error("Caught Exception ('{$e->getMessage()}')\n{$e}\n");

            return null;
        }
    }

    public function getAllXeroInvoice($where = null, $if_modified_since = null, $i_ds = null)
    {
        \Log::channel('xeroLog')->info('Get Xero All Invoice function work');
        try {
            return $this->apiInstance->getInvoices($this->xeroTenantId(), $if_modified_since, $where, $order = null, $i_ds);
        } catch (\throwable $e) {
            \Log::channel('xeroLog')->error("Caught Exception ('{$e->getMessage()}')\n{$e}\n");

            return [];
        }
    }

    public function get429()
    {
        try {
            $result = $this->apiInstance->getInvoice($this->xeroTenantId(), 429);

            return $result->getInvoices()[0];
        } catch (\throwable $e) {
            dd($e);
        }
    }
}
