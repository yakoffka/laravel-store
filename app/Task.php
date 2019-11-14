<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\{Taskspriority, Tasksstatus};
use Illuminate\Support\Carbon;
use App\Customevent;
use App\Mail\TaskNotification;

class Task extends Model
{
    use SoftDeletes;
    
    protected $guarded = [];
    protected $perPage = 30;
    private $event_type = '';
    
    public function getMaster () {
        return $this->belongsTo(User::class, 'master_user_id');
    }

    public function getSlave () {
        return $this->belongsTo(User::class, 'slave_user_id');
    }

    public function getPriority () {
        return $this->belongsTo(Taskspriority::class, 'taskspriority_id');
    }
    
    public function getStatus () {
        return $this->belongsTo(Tasksstatus::class, 'tasksstatus_id');
    }


    /**
     * set setCreator from auth user
     * 
     * @param  Task $task
     * @return  Task $task
     */
    public function setCreator () {
        info(__METHOD__);
        $this->added_by_user_id = auth()->user()->id;
        return $this;
    }

    /**
     * set setCreator from auth user
     * 
     * @param  Task $task
     * @return  Task $task
     */
    public function setEditor () {
        info(__METHOD__);
        $this->edited_by_user_id = auth()->user()->id;
        return $this;
    }

    /**
     * Create records in table events.
     *
     * @return Task $task
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
     * @return Task $task
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
                new TaskNotification($this->getTable(), $this->id, $this->name, auth()->user()->name, $this->event_type)
            );

            // restarting the queue to make sure they are started
            if( !empty(config('custom.exec_queue_work')) ) {
                info(__METHOD__ . ': ' . exec(config('custom.exec_queue_work')));
            }
        }
        return $this;
    }

    public function setFlashMess()
    {
        info(__METHOD__);
        $message = __('Task__success', ['name' => $this->name, 'type_act' => __('feminine_'.$this->event_type)]);
        session()->flash('message', $message);
        return $this;
    }
}
