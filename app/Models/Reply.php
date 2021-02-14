<?php

namespace App\Models;

use App\Models\Traits\RecordsActivity;
use App\Models\Traits\Favorable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
     * A reply belongs to a user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * A thread belongs to a user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
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
