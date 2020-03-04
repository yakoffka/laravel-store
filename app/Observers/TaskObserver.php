<?php

namespace App\Observers;

use App\Task;

class TaskObserver
{
    /**
     * Handle the task "creating" event.
     *
     * @param  \App\Task  $task
     * @return void
     */
    public function creating(Task $task)
    {
        $task->setCreator();
    }

    /**
     * Handle the task "created" event.
     *
     * @param  \App\Task  $task
     * @return void
     */
    public function created(Task $task)
    {
        $task->createCustomevent()->sendEmailNotification()->setFlashMess();
    }


    /**
     * Handle the task "updating" event.
     *
     * @param  \App\Task  $task
     * @return void
     */
    public function updating(Task $task)
    {
        $task->setEditor();
    }

    /**
     * Handle the task "updated" event.
     *
     * @param  \App\Task  $task
     * @return void
     */
    public function updated(Task $task)
    {
        $task->createCustomevent()->sendEmailNotification()->setFlashMess();
    }


    /**
     * Handle the task "deleting" event.
     *
     * @param  \App\Task  $task
     * @return void
     */
    public function deleting(Task $task)
    {
    }

    /**
     * Handle the task "deleted" event.
     *
     * @param  \App\Task  $task
     * @return void
     */
    public function deleted(Task $task)
    {
        $task->createCustomevent()->sendEmailNotification()->setFlashMess();
    }


    /**
     * Handle the task "restored" event.
     *
     * @param  \App\Task  $task
     * @return void
     */
    public function restored(Task $task)
    {
    }

    /**
     * Handle the task "force deleted" event.
     *
     * @param  \App\Task  $task
     * @return void
     */
    public function forceDeleted(Task $task)
    {
    }
}
