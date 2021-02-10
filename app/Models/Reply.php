<?php

namespace App\Models;

use App\Models\Traits\RecordsActivity;
use App\Models\Traits\Favorable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use HasFactory, Favorable, RecordsActivity;

    protected $guarded = [];

    protected $with = ['owner', 'favorites'];

    /**
     * A reply belongs to a user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }

}
