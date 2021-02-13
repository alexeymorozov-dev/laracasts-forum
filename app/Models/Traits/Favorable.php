<?php


namespace App\Models\Traits;


use App\Models\Favorite;

trait Favorable
{

    public function getFavoritesCountAttribute()
    {
        return $this->favorites->count();
    }

    /**
     * Determine if the current reply ha been favorited
     *
     * @return mixed
     */
    public function isFavorited()
    {
        return !! $this->favorites->where('user_id', auth()->id())->count();
    }

    public function getIsFavoritedAttribute()
    {
        return $this->isFavorited();
    }

    /**
     * Favorite the current reply
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function favorite()
    {
        $attributes = ['user_id' => auth()->id()];

        if (!$this->favorites()->where($attributes)->exists()) {
            return $this->favorites()->create($attributes);
        }
    }

    /**
     * Unfavorite the current reply
     */
    public function unfavorite()
    {
        $attributes = ['user_id' => auth()->id()];

        $this->favorites()->where($attributes)->delete();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favorited');
    }
}
