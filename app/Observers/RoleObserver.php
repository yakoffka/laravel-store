<?php

namespace App\Observers;

use App\Role;

class RoleObserver
{
    /**
     * Handle the role "creating" event.
     *
     * @param  \App\Role  $role
     * @return void
     */
    public function creating(Role $role)
    {
        $role->setCreator();
    }

    /**
     * Handle the role "created" event.
     *
     * @param  \App\Role  $role
     * @return void
     */
    public function created(Role $role)
    {
        $role->setPermissions()->createCustomevent()->sendEmailNotification()->setFlashMess();
    }


    /**
     * Handle the role "updating" event.
     *
     * @param  \App\Role  $role
     * @return void
     */
    public function updating(Role $role)
    {
        $role->setEditor();
    }

    /**
     * Handle the role "updated" event.
     *
     * @param  \App\Role  $role
     * @return void
     */
    public function updated(Role $role)
    {
        $role->createCustomevent()->sendEmailNotification()->setFlashMess();
    }


    /**
     * Handle the role "deleting" event.
     *
     * @param  \App\Role  $role
     * @return void
     */
    public function deleting(Role $role)
    {
    }

    /**
     * Handle the role "deleted" event.
     *
     * @param  \App\Role  $role
     * @return void
     */
    public function deleted(Role $role)
    {
        $role->createCustomevent()->sendEmailNotification()->setFlashMess();
    }


    /**
     * Handle the role "restored" event.
     *
     * @param  \App\Role  $role
     * @return void
     */
    public function restored(Role $role)
    {
    }

    /**
     * Handle the role "force deleted" event.
     *
     * @param  \App\Role  $role
     * @return void
     */
    public function forceDeleted(Role $role)
    {
    }
}
