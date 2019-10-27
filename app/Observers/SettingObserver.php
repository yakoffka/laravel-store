<?php

namespace App\Observers;

use App\Setting;

class SettingObserver
{
    /**
     * Handle the setting "creating" event.
     *
     * @param  \App\Setting  $setting
     * @return void
     */
    public function creating(Setting $setting)
    {
        info(__METHOD__);
    }

    /**
     * Handle the setting "created" event.
     *
     * @param  \App\Setting  $setting
     * @return void
     */
    public function created(Setting $setting)
    {
        info(__METHOD__);
    }


    /**
     * Handle the setting "updating" event.
     *
     * @param  \App\Setting  $setting
     * @return void
     */
    public function updating(Setting $setting)
    {
        info(__METHOD__);
    }

    /**
     * Handle the setting "updated" event.
     *
     * @param  \App\Setting  $setting
     * @return void
     */
    public function updated(Setting $setting)
    {
        info(__METHOD__);
    }


    /**
     * Handle the setting "deleting" event.
     *
     * @param  \App\Setting  $setting
     * @return void
     */
    public function deleting(Setting $setting)
    {
        info(__METHOD__);
    }

    /**
     * Handle the setting "deleted" event.
     *
     * @param  \App\Setting  $setting
     * @return void
     */
    public function deleted(Setting $setting)
    {
        info(__METHOD__);
    }


    /**
     * Handle the setting "restored" event.
     *
     * @param  \App\Setting  $setting
     * @return void
     */
    public function restored(Setting $setting)
    {
        info(__METHOD__);
    }

    /**
     * Handle the setting "force deleted" event.
     *
     * @param  \App\Setting  $setting
     * @return void
     */
    public function forceDeleted(Setting $setting)
    {
        info(__METHOD__);
    }
}
