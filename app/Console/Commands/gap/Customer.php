<?php

namespace App\Console\Commands\gap;

use Illuminate\Console\Command;

use App\Helpers\NHelpers;

class Customer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gap:customer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Customer API';

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
    }
}
