<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\belongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Zizaco\Entrust\EntrustRole;
use Illuminate\Support\Carbon;
use App\Mail\RoleNotification;
use Illuminate\Support\Facades\DB;

/**
 * App\Role
 *
 * @property int $id
 * @property string $name
 * @property string|null $display_name
 * @property string|null $description
 * @property int $added_by_user_id
 * @property int|null $edited_by_user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User $creator
 * @property-read User|null $editor
 * @property-read Collection|Permission[] $perms
 * @property-read int|null $perms_count
 * @property-read Collection|User[] $users
 * @property-read int|null $users_count
 * @method static Builder|Role newModelQuery()
 * @method static Builder|Role newQuery()
 * @method static Builder|Role query()
 * @method static Builder|Role whereAddedByUserId($value)
 * @method static Builder|Role whereCreatedAt($value)
 * @method static Builder|Role whereDescription($value)
 * @method static Builder|Role whereDisplayName($value)
 * @method static Builder|Role whereEditedByUserId($value)
 * @method static Builder|Role whereId($value)
 * @method static Builder|Role whereName($value)
 * @method static Builder|Role whereUpdatedAt($value)
 * @mixin Eloquent
 */
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
    private string $event_description = '';

    /**
     * Many-to-Many relations with the user model.
     *
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * Many-to-One relations with the user model.
     *
     * @return belongsTo
     */
    public function creator(): belongsTo
    {
        return $this->belongsTo(User::class, 'added_by_user_id');
    }

    /**
     * Many-to-One relations with the user model.
     *
     * @return belongsTo
     */
    public function editor(): belongsTo
    {
        return $this->belongsTo(User::class, 'edited_by_user_id');
    }

    /**
     * set setCreator from auth user
     *
     * @return Role $role
     */
    public function setCreator(): self
    {
        $this->added_by_user_id = auth()->user() ? auth()->user()->id : User::SYSUID;
        return $this;
    }

    /**
     * set setCreator from auth user
     *
     * @return Role $role
     */
    public function setEditor(): self
    {
        $this->edited_by_user_id = auth()->user() ? auth()->user()->id : User::SYSUID;
        return $this;
    }

    /**
     * Attaches the permissions to the role that are transferred in the request,
     * provided that the authorized user has them
     *
     * @return Role $role
     */
    public function setPermissions(): self
    {
        $permissions = Permission::all()->toArray();
        $attach_roles = [];
        foreach ($permissions as $permission) {

            // attach Permission
            if (
                request($permission['name']) === 'on'
                && !$this->perms->contains('name', $permission['name'])
                && auth()->user()->can($permission['name'])
            ) {
                $this->attachPermission($permission['id']);
                $attach_roles[] = $permission['description'];

                // take Permission
            } elseif (
                empty(request($permission['name']))
                && $this->perms->contains('name', $permission['name'])
                && auth()->user()->can($permission['name'])
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

        if ((!empty($attach_roles) || !empty($take_roles)) && !$this->isDirty()) {
            $this->touch();
        }

        return $this;
    }

    /**
     * Create records in table events.
     *
     * @return $this
     */
    public function createCustomevent(): self
    {
        $this->event_type = debug_backtrace()[1]['function'];
        $attr = $this->getAttributes();
        $dirty = $this->getDirty();
        $original = $this->getOriginal();
        // dd($attr, $dirty, $original);

        $details = [];
        foreach ($attr as $property => $value) {
            if (array_key_exists($property, $dirty) or !$dirty) {
                $details[] = [
                    $property,
                    $original[$property] ?? FALSE,
                    $dirty[$property] ?? FALSE,
                ];
            }
        }

        Customevent::create([
            'user_id' => auth()->user() ? auth()->user()->id : User::SYSUID,
            'model' => $this->getTable(),
            'model_id' => $this->id,
            'model_name' => $this->name,
            'type' => $this->event_type,
            'description' => $this->event_description ?? FALSE,
            'details' => serialize($details) ?? '',
        ]);
        return $this;
    }

    /**
     * Create event notification.
     *
     * @return $this
     */
    public function sendEmailNotification(): self
    {
        $namesetting = 'settings.email_' . $this->getTable() . '_' . $this->event_type;
        $setting = config($namesetting);
        info(__METHOD__ . ' ' . $namesetting . ' = ' . $setting);

        if ( $setting === '1' ) {
            $to = auth()->user();

            $bcc = array_merge(config('mail.mail_bcc'), explode(', ', config('settigs.additional_email_bcc')));
            $bcc = array_diff($bcc, ['', auth()->user() ? auth()->user()->email : '', config('mail.email_send_delay')]);
            $bcc = array_unique($bcc);

            \Mail::to($to)->bcc($bcc)->later(
                Carbon::now()->addMinutes(config('mail.email_send_delay')),
                new RoleNotification($this->getTable(), $this->id, $this->name, auth()->user()->name, $this->event_type)
            );

            // restarting the queue to make sure they are started
            /*if (!empty(config('custom.exec_queue_work'))) {
                info(__METHOD__ . ': ' . exec(config('custom.exec_queue_work')));
            }*/
        }
        return $this;
    }

    /**
     * @return $this
     */
    public function setFlashMess(): self
    {
        $message = __('Role__success', ['name' => $this->name, 'type_act' => __('feminine_' . $this->event_type)]);
        session()->flash('message', $message . $this->event_description);
        return $this;
    }
}
