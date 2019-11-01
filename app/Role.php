<?php

namespace App;

use Zizaco\Entrust\EntrustRole;
use Illuminate\Support\Carbon;
use App\Mail\RoleNotification;
use App\Customevent;
use App\Permission;
use Illuminate\Support\Facades\DB;

class Role extends EntrustRole
{
    /*
    *  The Role model has three main attributes:
    *  
    *  name — Unique name for the Role, used for looking up role information in the application layer. For example: "admin", "owner", "employee".
    *  display_name — Human readable name for the Role. Not necessarily unique and optional. For example: "User Administrator", "Project Owner", "Widget Co. Employee".
    *  description — A more detailed explanation of what the Role does. Also optional.
    */

    protected $guarded = [];
    private $event_description = '';

    /**
    * Many-to-Many relations with the user model.
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
    */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    /**
    * Many-to-One relations with the user model.
    *
    * @return \Illuminate\Database\Eloquent\Relations\belongsTo
    */
    public function creator() {
        return $this->belongsTo(User::class, 'added_by_user_id');
    }

    /**
    * Many-to-One relations with the user model.
    *
    * @return \Illuminate\Database\Eloquent\Relations\belongsTo
    */
    public function editor() {
        return $this->belongsTo(User::class, 'edited_by_user_id');
    }


    /**
     * set setCreator from auth user
     * 
     * @param  Role $role
     * @return  Role $role
     */
    public function setCreator () {
        info(__METHOD__);
        $this->added_by_user_id = auth()->user()->id;
        return $this;
    }

    /**
     * set setCreator from auth user
     * 
     * @param  Role $role
     * @return  Role $role
     */
    public function setEditor () {
        info(__METHOD__);
        $this->edited_by_user_id = auth()->user()->id;
        return $this;
    }

    /**
     * Attaches the permissions to the role that are transferred in the request, 
     * provided that the authorized user has them
     * 
     * @param  Role $role
     * @return  Role $role
     */
    public function setAviablePermissions () {
        info(__METHOD__);
        $permissions = Permission::all()->toArray();
        $attach_roles = [];
        foreach ( $permissions as $permission ) {
            // attach Permission
            if (
                request($permission['name']) === 'on' 
                and !$this->perms->contains('name', $permission['name']) 
                and auth()->user()->can($permission['name']) 
            ) {
                $this->attachPermission($permission['id']);
                $attach_roles[] = $permission['description'];

            // take Permission
            } elseif ( 
                empty(request($permission['name'])) 
                and $this->perms->contains('name', $permission['name']) 
                and auth()->user()->can($permission['name']) 
            ) {
                $take_role = DB::table('permission_role')->where([
                    ['permission_id', '=', $permission['id']],
                    ['role_id', '=', $this->id],
                ])->delete();
                $take_roles[] = $permission['description'];
            }
        }
        $this->event_description = 
              (!empty($attach_roles) ? ' Добавлены разрешения (' . count($attach_roles) . '): ' . implode('; ', $attach_roles) . '.' : '')
            . (!empty($take_roles) ? ' Удалены разрешения (' . count($take_roles) . '): ' . implode('; ', $take_roles) . '.' : '');
        
        if ( !$this->isDirty() and (!empty($attach_roles) or !empty($take_roles)) ) {
            $this->touch();
        }

        return $this;
    }

    /**
     * Create records in table events.
     *
     * @return Role $role
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
     * @return Role $role
     */
    public function sendEmailNotification()
    {
        info(__METHOD__);

        $event_type = $this->event_type;
        $namesetting = 'settings.email_' . $this->getTable() . '_' . $event_type;
        $setting = config($namesetting);

        info(__METHOD__ . ' ' . $namesetting . ' = ' . $setting);

        if ( $setting === '1' ) {

            $bcc = config('mail.mail_bcc');
            $additional_email_bcc = config('settigs.additional_email_bcc');
            if ( $additional_email_bcc->value ) {
                $bcc = array_merge( $bcc, explode(', ', $additional_email_bcc->value));
            }
            $email_send_delay = Setting::all()->firstWhere('name', 'email_send_delay');
            $when = Carbon::now()->addMinutes($email_send_delay);
            $username = auth()->user() ? auth()->user()->name : 'Unregistered';

            \Mail::to( auth()->user() ?? config('mail.from.address') )
                ->bcc($bcc)
                ->later( 
                    $when, 
                    new RoleNotification($this, $event_type, $username)
                );
        }
        return $this;
    }

    public function setFlashMess()
    {
        info(__METHOD__);
        $message = __('Role__success', ['name' => $this->name, 'type_act' => __('feminine_'.$this->event_type)]);
        session()->flash('message', $message . $this->event_description);
        return $this;
    }
}
