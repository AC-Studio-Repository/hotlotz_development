<?php

namespace App\Listeners;

use App\Events\CategoryCreatedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CategoryCreatedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  CategoryCreatedEvent  $event
     * @return void
     */
    public function handle(CategoryCreatedEvent $event)
    {
        \Log::info('New Category is created.');
        \Log::info('New Category : '.$event->category);
    }
}
