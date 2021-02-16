<?php

namespace App\Models;

use App\Models\Traits\Favorable;
use App\Models\Traits\RecordsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reply extends Model
{
    use HasFactory, Favorable, RecordsActivity;

    /**
     * Don't auto-apply mass assignment protection
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The relations to eager load on every query
     *
     * @var array
     */
    protected $with = ['owner', 'favorites'];

    protected $appends = ['favoritesCount', 'isFavorited'];

    /**
     *
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($reply) {
            $reply->thread->increment('replies_count');
        });

        static::deleted(function ($reply) {
            $reply->thread->decrement('replies_count');
        });

    }


    /**
     * A reply belongs to a user
     *
     * @return BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * A thread belongs to a user
     *
     * @return BelongsTo
     */
    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }

    /**
     * The path to the reply
     *
     * @return string
     */
    public function path()
    {
        return $this->thread->path() . '#reply_' . $this->id;
    }

}
