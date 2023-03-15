<?php

namespace App\Console\Commands\xero;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Modules\Xero\Repositories\XeroInvoiceRepository;
use App\Modules\Xero\Webhook\Updated as XeroWebhookUpdate;
use App\Modules\Xero\Accounting\Automate as AccountingAutomate;

class WebhookUpdateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'xero:webhook-update
    {--T|type=bill : Access type (bill & invoice) to update webhook; default bill. (e.g, -T [bill ?? invoice] or --type [all ?? bill ?? invoice])}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Xero webhook update';

    protected $xeroInvoiceRepository;

    protected $xeroWebhookUpdate;

    protected $accountingAutomate;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        XeroInvoiceRepository $xeroInvoiceRepository,
        XeroWebhookUpdate $xeroWebhookUpdate,
        AccountingAutomate $accountingAutomate
    ) {
        parent::__construct();
        $this->xeroInvoiceRepository = $xeroInvoiceRepository;
        $this->xeroWebhookUpdate = $xeroWebhookUpdate;
        $this->accountingAutomate = $accountingAutomate;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $if_modified_since = $this->ask('What is modified since ? e.g; 2020-12-01 00:00:00 or blank');

        $startDate = $this->ask('What is Start Date ? e.g; 2020,12,1 or blank');

        $endDate = $this->ask('What is End Date ? e.g; 2020,12,1 or blank');

        $i_ds = $this->ask("What is invoice id ? e.g; comma-separated list of InvoicesIDs or blank");

        if ($i_ds != null) {
            $i_ds = explode(',', $i_ds);
        }

        $this->info(date('Y-m-d H:i:s').' ======= Start - Xero webhook update Command =======');
        \Log::channel('xeroLog')->info('======= Start - Xero webhook update Command =======');
        try {
            $type = $this->option('type');

            if ($type == 'bill') {
                $where = 'Type=="ACCPAY"';
            }

            if ($type == 'invoice') {
                $where = 'Type=="ACCREC"';
            }

            if ($startDate) {
                $where .= ' AND UpdatedDateUTC >= DateTime('.$startDate.')';
            }

            if ($endDate) {
                $where .= ' AND UpdatedDateUTC <= DateTime('.$endDate.')';
            }
            $invoices = $this->accountingAutomate->getAllXeroInvoice($where, null, $i_ds);

            foreach ($invoices as $invoice) {
                $this->xeroWebhookUpdate->invoiceUpdated($invoice);
            }
            $this->info(date('Y-m-d H:i:s').' ======= End - Xero webhook update Command =======');
            \Log::channel('xeroLog')->info('======= End - Xero webhook update Command =======');
        } catch (\Throwable $th) {
            \Log::channel('xeroLog')->error('======= Failed - Xero webhook update Command =======');
            \Log::channel('xeroLog')->error("Caught Exception ('{$th->getMessage()}')\n{$th}\n");

            throw $th;
        }
    }
}
