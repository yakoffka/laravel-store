<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use App\Mail\CommentNotification;
use Mail;
use Str;

/**
 * App\Comment
 *
 * @property int $id
 * @property string|null $name
 * @property int $product_id
 * @property int $user_id
 * @property string $user_name
 * @property string $body
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User $creator
 * @property-read Product $product
 * @method static Builder|Comment newModelQuery()
 * @method static Builder|Comment newQuery()
 * @method static Builder|Comment query()
 * @method static Builder|Comment whereBody($value)
 * @method static Builder|Comment whereCreatedAt($value)
 * @method static Builder|Comment whereId($value)
 * @method static Builder|Comment whereName($value)
 * @method static Builder|Comment whereProductId($value)
 * @method static Builder|Comment whereUpdatedAt($value)
 * @method static Builder|Comment whereUserId($value)
 * @method static Builder|Comment whereUserName($value)
 * @mixin Eloquent
 */
class Comment extends Model
{
    protected $guarded = [];
    protected $perPage = 15;
    private string $event_type = '';


    /**
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * @return BelongsTo
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * : @return $this
     */
    public function setAuthor(): self
    {
        if (auth()->user()) {
            $this->user_id = auth()->id();
            $this->user_name = auth()->user()->name;
        } else {
            $this->user_id = User::URUID; // unregistered user id
            $this->user_name = request('user_name') ?? 'Anonimous'; // @todo
        }
        return $this;
    }

    /**
     * : @return $this
     */
    public function setName(): self
    {
        $this->name = Str::limit($this->body, 20);
        return $this;
    }

    /**
     * : @return $this
     */
    public function transformBody(): self
    {
        $this->body = str_replace(["\r\n", "\r", "\n"], '<br>', $this->body);
        return $this;
    }

    /**
     * : @return $this
     */
    public function breakBody(): string
    {
        return str_replace('<br>', "\r\n", $this->body);
    }

    /**
     * @return string
     */
    public function formattedCreatedAt(): string
    {
        return $this->created_at->format('Y.m.d H:i');
    }

    /**
     * @return string
     */
    public function formattedUpdatedAt(): string
    {
        return $this->created_at->format('Y.m.d H:i');
    }

    /**
     * Create records in table events.
     *
     * : @return $this $comment
     */
    public function createCustomevent(): self
    {
        $this->event_type = debug_backtrace()[1]['function'];
        $attr = $this->getAttributes();
        $dirty = $this->getDirty();
        $original = $this->getOriginal();

        $details = [];
        foreach ($attr as $property => $value) {
            if (array_key_exists($property, $dirty) && !$dirty) {
                $details[] = [
                    $property,
                    $original[$property] ?? FALSE,
                    $dirty[$property] ?? FALSE,
                ];
            }
        }

        Customevent::create([
            'user_id' => $this->user_id,
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
     * : @return $this $comment
     */
    public function sendEmailNotification(): self
    {
        $namesetting = 'settings.email_' . $this->getTable() . '_' . $this->event_type;
        $setting = config($namesetting);

        if ( $setting === '1' ) {
            $to = auth()->user() ?? config('mail.from.address');

            $bcc = array_merge(config('mail.mail_bcc'), explode(', ', config('settigs.additional_email_bcc')));
            $bcc = array_diff($bcc, ['', auth()->user() ? auth()->user()->email : '', config('mail.email_send_delay')]);
            $bcc = array_unique($bcc);

            Mail::to($to)->bcc($bcc)->later(
                Carbon::now()->addMinutes(config('mail.email_send_delay')),
                new CommentNotification($this->getTable(), $this->id, $this->name, $this->user_name, $this->event_type, $this->product_id, $this->body)
            );

            // restarting the queue to make sure they are started
            if (!empty(config('custom.exec_queue_work'))) {
                info(__METHOD__ . ': ' . exec(config('custom.exec_queue_work')));
            }
        }
        return $this;
    }

    /**
     * : @return $this
     */
    public function setFlashMess(): self
    {
        $message = __('Comment__success', ['name' => $this->name, 'type_act' => __('masculine_' . $this->event_type)]);
        session()->flash('message', $message);
        return $this;
    }
}
