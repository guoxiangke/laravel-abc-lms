<?php

namespace App\Listeners;

use Illuminate\Log\Events\MessageLogged;

class LoggingListener
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
     * @param  MessageLogged  $event
     * @return void
     */
    public function handle(MessageLogged $event)
    {
        if ($event->level == 'error') {
            $title = 'Error:'.config('app.name', 'ABC-lms').'-'.config('app.env', 'local');
            bark_notify($title, $event->message);
        }
    }
}
