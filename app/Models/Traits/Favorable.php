<?php


namespace App\Models\Traits;


use App\Models\Favorite;

trait Favorable
{

    public function getFavoritesCountAttribute()
    {
        return $this->favorites->count();
    }

    public function isFavorited()
    {
        return $this->favorites->where('user_id', auth()->id())->count();
    }

    public function favorite()
    {
        $attributes = ['user_id' => auth()->id()];

        if (!$this->favorites()->where($attributes)->exists()) {
            return $this->favorites()->create($attributes);
        }
    }

    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favorited');
    }
}
