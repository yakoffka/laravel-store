<?php

namespace App\Observers;

use App\Setting;

class SettingObserver
{
    /**
     * Handle the setting "updating" event.
     *
     * @param  \App\Setting  $setting
     * @return void
     */
    public function updating(Setting $setting)
    {
        info(__METHOD__);
        $setting->setEditor()->writeConfig();
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
        $setting->createCustomevent()->sendEmailNotification()->setFlashMess();
    }
}
