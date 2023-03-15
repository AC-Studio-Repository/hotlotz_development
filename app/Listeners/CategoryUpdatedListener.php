<?php

namespace App\Listeners;

use App\Events\CategoryUpdatedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CategoryUpdatedListener
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
     * @param  CategoryUpdatedEvent  $event
     * @return void
     */
    public function handle(CategoryUpdatedEvent $event)
    {
        \Log::info('Category '.$event->category->name.'\'s '.$event->status.' is updated.');
    }
}
