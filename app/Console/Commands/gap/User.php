<?php

namespace App\Console\Commands\gap;

use Illuminate\Console\Command;

use App\Helpers\NHelpers;

class User extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gap:user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test User API';

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
        $this->getClientId();
    }

    private function getClientId(){
        $config = NHelpers::getGapConfig();

        $apiInstance = new \GAP\Api\UserApi(
            new \GuzzleHttp\Client(),
            $config
        );

        try {
            $result = $apiInstance->getClientId();
            $this->info('Get Client Id -> SUCCESS');
            \Log::info($result);
        } catch (Exception $e) {
            $this->error('Get Client Id -> FAIL');
            \Log::error($e);
        }
    }
}
