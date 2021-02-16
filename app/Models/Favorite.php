<?php

namespace App\Models;

use App\Models\Traits\RecordsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Favorite extends Model
{
    use HasFactory, RecordsActivity;

    protected $guarded = [];

    /**
     * @return MorphTo
     */
    public function favorited()
    {
        return $this->morphTo();
    }
}
