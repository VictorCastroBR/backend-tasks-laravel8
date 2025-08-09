<?php

namespace App\Listeners;

use App\Events\TaskCompleted;
use App\Mail\TaskCompletedMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\MaiL;

class SendTaskCompletedMail
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
     * @param  \App\Events\TaskCompleted  $event
     * @return void
     */
    public function handle(TaskCompleted $event)
    {
        $task = $event->task->load('user');
        Mail::to($task->user->email)->queue(new TaskCompletedMail($task));
    }
}
