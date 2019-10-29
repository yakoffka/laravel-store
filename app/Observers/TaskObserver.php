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
        info(__METHOD__);
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
        info(__METHOD__);
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
        info(__METHOD__);
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
        info(__METHOD__);
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
        info(__METHOD__);
    }

    /**
     * Handle the task "deleted" event.
     *
     * @param  \App\Task  $task
     * @return void
     */
    public function deleted(Task $task)
    {
        info(__METHOD__);
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
        info(__METHOD__);
    }

    /**
     * Handle the task "force deleted" event.
     *
     * @param  \App\Task  $task
     * @return void
     */
    public function forceDeleted(Task $task)
    {
        info(__METHOD__);
    }
}
