<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use App\Customevent;
use App\Mail\SettingNotification;

class Setting extends Model
{
    protected $guarded = [];
    private $event_type = '';


    /**
     * set setCreator from auth user
     * 
     * @param  Setting $setting
     * @return  Setting $setting
     */
    public function setEditor () {
        info(__METHOD__);
        $this->edited_by_user_id = auth()->user()->id;
        return $this;
    }

    /**
     * Overwrite the configuration file config/settings.php with data from the database
     *
     * @return Setting $setting
     */
    public function writeConfig()
    {
        info(__METHOD__);
        $settings = Setting::get();
        if ( $settings->count() ) {
            $path = __DIR__ . '/../config/settings.php';
            info($path);
            $fp = fopen($path, 'w');
            fwrite($fp, "<?php\n\n// this file is automatically generated in '" . __METHOD__ . "!' \n\nreturn [\n\n");

            foreach ( $settings as $setting_ ) {
                fwrite($fp, "\t'$setting_->name' => '$setting_->value', // $setting_->display_name\n");
            }

            fwrite($fp, "\n];");
            fclose($fp);
        }
        return $this;
    }

    /**
     * Create records in table events.
     *
     * @return Setting $setting
     */
    public function createCustomevent()
    {
        info(__METHOD__);
        $this->event_type = debug_backtrace()[1]['function'];
        $attr = $this->getAttributes();
        $dirty = $this->getDirty();
        $original = $this->getOriginal();
        // dd($attr, $dirty, $original);

        $details = [];
        foreach ( $attr as $property => $value ) {
            if ( array_key_exists( $property, $dirty ) or !$dirty ) {
                $details[] = [ 
                    $property, 
                    $original[$property] ?? FALSE, 
                    $dirty[$property] ?? FALSE,
                ];
            }
        }

        Customevent::create([
            'user_id' => auth()->user()->id,
            'model' => $this->getTable(),
            'model_id' => $this->id,
            'model_name' => $this->name,
            'type' => $this->event_type,
            'description' => $this->event_description ?? FALSE,
            'details' => serialize($details) ?? FALSE,
        ]);
        return $this;
    }


    /**
     * Create event notification.
     * 
     * @return Setting $setting
     */
    public function sendEmailNotification()
    {
        info(__METHOD__);
        $namesetting = 'settings.email_' . $this->getTable() . '_' . $this->event_type;
        $setting = config($namesetting);
        info(__METHOD__ . ' ' . $namesetting . ' = ' . $setting);

        if ( $setting === '1' ) {
            $to = auth()->user();

            $bcc = array_merge( config('mail.mail_bcc'), explode(', ', config('settigs.additional_email_bcc')));
            $bcc = array_diff($bcc, ['', auth()->user() ? auth()->user()->email : '', config('mail.email_send_delay')]);
            $bcc = array_unique($bcc);

            \Mail::to($to)->bcc($bcc)->later( 
                Carbon::now()->addMinutes(config('mail.email_send_delay')), 
                new SettingNotification($this->getTable(), $this->id, $this->name, auth()->user()->name, $this->event_type)
            );
        }
        return $this;
    }

    public function setFlashMess()
    {
        info(__METHOD__);
        $message = __('Setting__success', ['name' => $this->name, 'type_act' => __('feminine_'.$this->event_type)]);
        session()->flash('message', $message);
        return $this;
    }
}
