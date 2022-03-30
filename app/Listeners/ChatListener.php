<?php

namespace App\Listeners;

use App\Events\Chat;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ChatListener
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
     * @param  \App\Events\Chat  $event
     * @return void
     */
    public function handle(Chat $event)
    {
        //
    }
}
