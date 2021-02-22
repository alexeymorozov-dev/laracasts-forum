<?php

namespace App\Models;

use App\Events\ThreadHasNewReply;
use App\Events\ThreadReceivedNewReply;
use App\Filters\ThreadFilters;
use App\Models\Traits\RecordsActivity;
use App\Models\Traits\RecordsVisits;
use Eloquent;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Redis;

/**
 * App\Models\Thread
 *
 * @property int $id
 * @property int $user_id
 * @property int $channel_id
 * @property-read int|null $replies_count
 * @property string $title
 * @property string $body
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Activity[] $activity
 * @property-read int|null $activity_count
 * @property-read Channel $channel
 * @property-read User $creator
 * @property-read bool $is_subscribed_to
 * @property-read Collection|Reply[] $replies
 * @property-read Collection|ThreadSubscription[] $subscriptions
 * @property-read int|null $subscriptions_count
 * @method static Builder|Thread filter($filters)
 * @method static Builder|Thread newModelQuery()
 * @method static Builder|Thread newQuery()
 * @method static Builder|Thread query()
 * @method static Builder|Thread whereBody($value)
 * @method static Builder|Thread whereChannelId($value)
 * @method static Builder|Thread whereCreatedAt($value)
 * @method static Builder|Thread whereId($value)
 * @method static Builder|Thread whereRepliesCount($value)
 * @method static Builder|Thread whereTitle($value)
 * @method static Builder|Thread whereUpdatedAt($value)
 * @method static Builder|Thread whereUserId($value)
 * @mixin Eloquent
 */
class Thread extends Model
{
    use HasFactory, RecordsActivity, RecordsVisits;

    /**
     * Don't auto-apply mass assignment protection.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The relationships to always eager-load.
     *
     * @var array
     */
    protected $with = ['channel'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['isSubscribedTo'];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('creator', function ($builder) {
            $builder->withCount('creator');
        });

        static::deleting(function ($thread) {
            $thread->replies->each->delete();
        });
    }

    /**
     * A full path to a thread
     *
     * @return string
     */
    public function path()
    {
        return "/threads/{$this->channel->slug}/{$this->id}";
    }

    /**
     * A thread belongs to a user
     *
     * @return BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * A thread belongs to a channel
     *
     * @return BelongsTo
     */
    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    /**
     * Add a reply to a thread
     * @param $reply
     * @return Model
     */
    public function addReply($reply)
    {
        $reply = $this->replies()->create($reply);

        event(new ThreadReceivedNewReply($reply));

        return $reply;
    }

    /**
     * A thread has many replies
     *
     * @return HasMany
     */
    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    /**
     * Apply all relevant thread filters
     *
     * @param $query
     * @param $filters
     * @return mixed
     */
    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }

    /**
     * Subscribe to the thread
     * @param null $userId
     * @return Thread
     */
    public function subscribe($userId = null)
    {
        $this->subscriptions()->create([
            'user_id' => $userId ?: auth()->id()
        ]);

        return $this;
    }

    /**
     * A thread has many subscribers
     *
     * @return HasMany
     */
    public function subscriptions()
    {
        return $this->hasMany(ThreadSubscription::class);
    }

    /**
     * Unsubscribe to the thread
     * @param null $userId
     */

    public function unsubscribe($userId = null)
    {
        $this->subscriptions()
            ->where(['user_id' => $userId ?: auth()->id()])
            ->delete();
    }

    /**
     * Is authenticated user subscribed to a thread
     *
     * @return bool
     */
    public function getIsSubscribedToAttribute()
    {
        return $this->subscriptions()
            ->where('user_id', auth()->id())
            ->exists();
    }

    /**
     * Fetch all relevant threads.
     *
     * @param ThreadFilters $filters
     * @param Channel $channel
     * @return LengthAwarePaginator
     */
    public static function getThreads(ThreadFilters $filters, Channel $channel)
    {
        $threads = Thread::latest()->filter($filters);

        if ($channel->exists) {
            $threads->where('channel_id', $channel->id);
        }

        return $threads->paginate(10);
    }

    /**
     * Determine if the thread has been updated since the user last read it.
     *
     * @param User $user
     * @return bool
     * @throws Exception
     */
    public function hasUpdatesFor(User $user)
    {
        $key = $user->visitedThreadCacheKey($this);

        return $this->updated_at > cache($key);
    }
}
