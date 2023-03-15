<?php

namespace App\Console\Commands\xero;

use Illuminate\Console\Command;
use XeroAPI\XeroPHP\Api\AccountingApi;
use Webfox\Xero\OauthCredentialManager;

class PaidInvoiceTaxChangeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'xero:paid-tax-change';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Xero Paided Invoic Tax Update';

    protected $xeroCredentials;

    protected $apiInstance;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
       OauthCredentialManager $xeroCredentials,
       AccountingApi $apiInstance
    ) {
        parent::__construct();
        $this->xeroCredentials = $xeroCredentials;
        $this->apiInstance = $apiInstance;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $i_ds = $this->ask("What is invoice id ? e.g; comma-separated list of InvoicesIDs or single id");

        if($i_ds != null){
            $i_ds = explode(',', $i_ds);
        }

        $this->info(date('Y-m-d H:i:s').' ======= Start - Xero Paided Invoic Tax Update Command =======');
        \Log::channel('xeroLog')->info('======= Start - Xero Paided Invoic Tax Update Command =======');
        try {
            foreach ($i_ds as $id) {

                $guid = $id;
                $getInvoice =$this->apiInstance->getInvoice($this->xeroCredentials->getTenantId(),$guid);
                $paymentId = $getInvoice->getInvoices()[0]->getPayments()[0]->getPaymentId();
                $paymentDate = $getInvoice->getInvoices()[0]->getPayments()[0]->getDate();
                $paymentRef = $getInvoice->getInvoices()[0]->getPayments()[0]->getReference();
                //[Payments:Delete]
                $payment = new \XeroAPI\XeroPHP\Models\Accounting\Payment;
                $payment->setPaymentID($paymentId)
                        ->setStatus(\XeroAPI\XeroPHP\Models\Accounting\PAYMENT::STATUS_DELETED);

                $this->apiInstance->deletePayment($this->xeroCredentials->getTenantId(),$paymentId,$payment);

                $lineItems = [];
                foreach ($getInvoice->getInvoices()[0]->getLineItems() as $key => $lineItem) {
                    $newLineItem = new \XeroAPI\XeroPHP\Models\Accounting\LineItem;
                    $newLineItem->setItemCode($lineItem['item_code'])
                        ->setDescription($lineItem['description'])
                        ->setQuantity(1)
                        ->setUnitAmount($lineItem['unit_amount'])
                        ->setTaxType('NONE')
                        ->setAccountCode($lineItem['account_code'])
                        ->setTracking($lineItem->getTracking());

                    $lineItems[] = $newLineItem;
                };

                //[Invoices:Update]
                $invoice = new \XeroAPI\XeroPHP\Models\Accounting\Invoice;
                $invoice->setLineItems($lineItems);
                $result = $this->apiInstance->updateInvoice($this->xeroCredentials->getTenantId(),$guid,$invoice);

                $bankaccount = new \XeroAPI\XeroPHP\Models\Accounting\Account;
                $bankaccount->setAccountID('915bdc04-6de7-4f14-a61d-67cec39a8413');

                $payment = new \XeroAPI\XeroPHP\Models\Accounting\Payment;
                $payment->setInvoice($result->getInvoices()[0])
                    ->setAccount($bankaccount)
                    ->setInvoiceNumber($result->getInvoices()[0]->getInvoiceNumber())
                    ->setDate($paymentDate)
                    ->setReference($paymentRef)
                    ->setAmount($result->getInvoices()[0]->getTotal());

                $this->apiInstance->createPayment($this->xeroCredentials->getTenantId(), $payment);
            }
            return 'Finish';

            $this->info(date('Y-m-d H:i:s').' ======= End - Xero Paided Invoic Tax Update Command =======');
            \Log::channel('xeroLog')->info('======= End - Xero Paided Invoic Tax Update Command =======');
        } catch (\Throwable $th) {
            \Log::channel('xeroLog')->error('======= Failed - Xero Paided Invoic Tax Update Command =======');
            \Log::channel('xeroLog')->error("Caught Exception ('{$th->getMessage()}')\n{$th}\n");

            throw $th;
        }
    }

}
