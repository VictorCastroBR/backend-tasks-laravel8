<?php

namespace App\Listeners;

use App\Events\TaskCreated;
use App\Mail\TaskCreatedMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendTaskCreatedMail
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
     * @param  \App\Events\TaskCreated  $event
     * @return void
     */
    public function handle(TaskCreated $event)
    {
        $task = $event->task->load('user');
        Mail::to($task->user->email)->queue(new TaskCreatedMail($task));
    }
}
