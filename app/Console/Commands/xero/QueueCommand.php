<?php

namespace App\Console\Commands\xero;

use Illuminate\Console\Command;
use App\Modules\Xero\Repositories\XeroInvoiceRepository;

class QueueCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'xero:queue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Xero Queue Test';


    protected $xeroInvoiceRepository;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        XeroInvoiceRepository $xeroInvoiceRepository
    ) {
        parent::__construct();

        $this->xeroInvoiceRepository = $xeroInvoiceRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info(date('Y-m-d H:i:s').' ======= Start - Xero Queue Test Command =======');
        \Log::channel('xeroLog')->info('======= Start - Xero Queue Test Command =======');
        try {
            $this->xeroInvoiceRepository->refreshCredential();
            $this->xeroInvoiceRepository->queueCommand();
            $this->info(date('Y-m-d H:i:s').' ======= End - Xero Queue Test Command =======');
            \Log::channel('xeroLog')->info('======= End - Xero Queue Test Command =======');
        } catch (\Throwable $th) {
            \Log::channel('xeroLog')->error('======= Failed - Xero Queue Test Command =======');
            \Log::channel('xeroLog')->error("Caught Exception ('{$th->getMessage()}')\n{$th}\n");

            throw $th;
        }
    }
}
