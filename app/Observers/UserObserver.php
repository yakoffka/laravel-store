<?php

namespace App\Observers;

use App\User;

class UserObserver
{
    /**
     * Handle the user "creating" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function creating(User $user)
    {
        info(__METHOD__);
    }

    /**
     * Handle the user "created" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function created(User $user)
    {
        info(__METHOD__);
        $user->createCustomevent()->sendEmailNotification()->setFlashMess();
    }


    /**
     * Handle the user "updating" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function updating(User $user)
    {
        info(__METHOD__);
    }

    /**
     * Handle the user "updated" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function updated(User $user)
    {
        info(__METHOD__);
        $user->createCustomevent()->sendEmailNotification()->setFlashMess();
    }


    /**
     * Handle the user "deleting" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function deleting(User $user)
    {
        info(__METHOD__);
    }

    /**
     * Handle the user "deleted" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function deleted(User $user)
    {
        info(__METHOD__);
        $user->createCustomevent()->sendEmailNotification()->setFlashMess();
    }


    /**
     * Handle the user "restored" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function restored(User $user)
    {
        info(__METHOD__);
    }

    /**
     * Handle the user "force forceDeleting" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function forceDeleting(User $user)
    {
        info(__METHOD__);
    }

    /**
     * Handle the user "force deleted" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        info(__METHOD__);
    }
}
