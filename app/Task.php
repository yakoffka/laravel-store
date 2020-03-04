<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use App\Mail\TaskNotification;
use Mail;

/**
 * App\Task
 *
 * @property int $id
 * @property int $master_user_id
 * @property int $slave_user_id
 * @property string $name
 * @property string $description
 * @property int $tasksstatus_id
 * @property int $taskspriority_id
 * @property string|null $comment_slave
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $added_by_user_id
 * @property int|null $edited_by_user_id
 * @property Carbon|null $deleted_at
 * @method static bool|null forceDelete()
 * @method static Builder|Task newModelQuery()
 * @method static Builder|Task newQuery()
 * @method static \Illuminate\Database\Query\Builder|Task onlyTrashed()
 * @method static Builder|Task query()
 * @method static bool|null restore()
 * @method static Builder|Task whereAddedByUserId($value)
 * @method static Builder|Task whereCommentSlave($value)
 * @method static Builder|Task whereCreatedAt($value)
 * @method static Builder|Task whereDeletedAt($value)
 * @method static Builder|Task whereDescription($value)
 * @method static Builder|Task whereEditedByUserId($value)
 * @method static Builder|Task whereId($value)
 * @method static Builder|Task whereMasterUserId($value)
 * @method static Builder|Task whereName($value)
 * @method static Builder|Task whereSlaveUserId($value)
 * @method static Builder|Task whereTaskspriorityId($value)
 * @method static Builder|Task whereTasksstatusId($value)
 * @method static Builder|Task whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Task withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Task withoutTrashed()
 * @mixin Eloquent
 */
class Task extends Model
{
    use SoftDeletes;

    protected $guarded = [];
    protected $perPage = 30;
    private string $event_type = '';

    /**
     * @return BelongsTo
     */
    public function getMaster(): BelongsTo
    {
        return $this->belongsTo(User::class, 'master_user_id');
    }

    /**
     * @return BelongsTo
     */
    public function getSlave(): BelongsTo
    {
        return $this->belongsTo(User::class, 'slave_user_id');
    }

    /**
     * @return BelongsTo
     */
    public function getPriority(): BelongsTo
    {
        return $this->belongsTo(Taskspriority::class, 'taskspriority_id');
    }

    /**
     * @return BelongsTo
     */
    public function getStatus(): BelongsTo
    {
        return $this->belongsTo(Tasksstatus::class, 'tasksstatus_id');
    }


    /**
     * set setCreator from auth user
     *
     * @return  Task $task
     */
    public function setCreator(): self
    {
        $this->added_by_user_id = auth()->user()->id;
        return $this;
    }

    /**
     * set setCreator from auth user
     *
     * @param Task $task
     * @return  Task $task
     */
    public function setEditor(): self
    {
        $this->edited_by_user_id = auth()->user()->id;
        return $this;
    }

    /**
     * Create records in table events.
     *
     *  @return $this $task
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
            'user_id' => auth()->user()->id,
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
     *  @return $this $task
     */
    public function sendEmailNotification(): self
    {
        $namesetting = 'settings.email_' . $this->getTable() . '_' . $this->event_type;
        $setting = config($namesetting);
        info(__METHOD__ . ' ' . $namesetting . ' = ' . $setting);

        if ($setting === '1') {
            $to = auth()->user();

            $bcc = array_merge(config('mail.mail_bcc'), explode(', ', config('settigs.additional_email_bcc')));
            $bcc = array_diff($bcc, ['', auth()->user() ? auth()->user()->email : '', config('mail.email_send_delay')]);
            $bcc = array_unique($bcc);

            Mail::to($to)->bcc($bcc)->later(
                Carbon::now()->addMinutes(config('mail.email_send_delay')),
                new TaskNotification($this->getTable(), $this->id, $this->name, auth()->user()->name, $this->event_type)
            );

            // restarting the queue to make sure they are started
            if (!empty(config('custom.exec_queue_work'))) {
                info(__METHOD__ . ': ' . exec(config('custom.exec_queue_work')));
            }
        }
        return $this;
    }

    /**
     *  @return $this
     */
    public function setFlashMess(): self
    {
        $message = __('Task__success', ['name' => $this->name, 'type_act' => __('feminine_' . $this->event_type)]);
        session()->flash('message', $message);
        return $this;
    }
}
