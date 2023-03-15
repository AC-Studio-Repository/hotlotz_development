<?php

namespace App\Console\Commands\gap;

use Illuminate\Console\Command;

use App\Helpers\NHelpers;

class Test extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gap:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run Test for POST and GET with/without Authrization';

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
        $first_name = 'Raymond';
        $last_name = 'Holt';

        $this->testGet($first_name, $last_name);
        $this->testPost($first_name, $last_name);
        $this->testGetSecure($first_name, $last_name);
        $this->testPostSecure($first_name, $last_name);
    }

    private function testGet($first_name, $last_name){
        $apiInstance = new \GAP\Api\TestApi(
            new \GuzzleHttp\Client()
        );

        try {
            $result = $apiInstance->getTestHello($first_name, $last_name);
            $this->info('Test GET -> SUCCESS');
            \Log::info($result);
        } catch (Exception $e) {
            $this->error('Test GET -> FAIL');
            \Log::error($e);
        }
    }

    private function testPost($first_name, $last_name){
        $apiInstance = new \GAP\Api\TestApi(
            new \GuzzleHttp\Client()
        );

        $data = [
            'FirstName' => $first_name,
            'LastName' => $last_name
        ];

        try {
            $result = $apiInstance->postTestHello($data);
            $this->info('Test POST -> SUCCESS');
            \Log::info($result);
        } catch (Exception $e) {
            $this->error('Test POST -> FAIL');
            \Log::error($e);
        }
    }

    private function testGetSecure($first_name, $last_name){
        $config = NHelpers::getGapConfig();

        $apiInstance = new \GAP\Api\TestApi(
            new \GuzzleHttp\Client(),
            $config
        );

        try {
            $result = $apiInstance->getTestHellosecure($first_name, $last_name);
            $this->info('Test Secure GET -> SUCCESS');
            \Log::info($result);
        } catch (Exception $e) {
            $this->error('Test Secure GET -> FAIL');
            \Log::error($e);
        }
    }

    private function testPostSecure($first_name, $last_name){
        $config = NHelpers::getGapConfig();

        $apiInstance = new \GAP\Api\TestApi(
            new \GuzzleHttp\Client(),
            $config
        );

        $data = [
            'FirstName' => $first_name,
            'LastName' => $last_name
        ];

        try {
            $result = $apiInstance->postTestHello($data);
            $this->info('Test Secure POST -> SUCCESS');
            \Log::info($result);
        } catch (Exception $e) {
            $this->error('Test Secure POST -> FAIL');
            \Log::error($e);
        }
    }
}
