<?php

namespace App\Console\Commands\Item;

use Illuminate\Console\Command;
use App\Events\Item\CreateThumbnailEvent;

class CreateThumbnail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'item:create-thumbnail {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create thumbnail for item';

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
        $this->info(date('Y-m-d H:i:s').' ======= Start - Create thumbnail for item Command =======');
        \Log::info('======= Start - Create thumbnail for item Command =======');

        event(new CreateThumbnailEvent($this->argument('id')));

        $this->info(date('Y-m-d H:i:s').' ======= End - Create thumbnail for item Command =======');
        \Log::info('======= End - Create thumbnail for item Command =======');
    }
}
