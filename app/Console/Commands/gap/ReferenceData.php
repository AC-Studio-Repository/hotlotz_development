<?php

namespace App\Console\Commands\gap;

use Illuminate\Console\Command;

use App\Helpers\NHelpers;

class ReferenceData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gap:referencedata';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Reference Data API';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $this->getCounteries();
    }

    private function getCounteries(){
        $config = NHelpers::getGapConfig();

        $apiInstance = new \GAP\Api\ReferenceDataApi(
            new \GuzzleHttp\Client(),
            $config
        );

        try {
            $result = $apiInstance->getCounteries();
            $this->info('Get Countries -> SUCCESS');
            \Log::info($result);
        } catch (Exception $e) {
            $this->error('Get Countries -> FAIL');
            \Log::error($e);
        }
    }
}
