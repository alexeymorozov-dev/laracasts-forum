<?php


namespace App\Models\Traits;


use App\Models\Favorite;

trait Favorable
{
    protected static function bootFavorable()
    {
        static::deleting(function ($model) {
            $model->favorites->each->delete();
        });
    }

    /**
     * Determine if the current reply ha been favorited
     *
     * @return mixed
     */
    public function isFavorited()
    {
        return !!$this->favorites->where('user_id', auth()->id())->count();
    }

    /**
     * Accessor to the reply's 'favorites_count' attribute
     *
     * @return mixed
     */
    public function getFavoritesCountAttribute()
    {
        return $this->favorites->count();
    }

    /**
     * Accessor to the reply's 'isFavorited' attribute
     *
     * @return bool|mixed
     */
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

        if (!$this->favorites()
            ->where($attributes)
            ->exists()) {
            return $this
                ->favorites()
                ->create($attributes);
        }
    }

    /**
     * Unfavorite the current reply
     */
    public function unfavorite()
    {
        $attributes = ['user_id' => auth()->id()];

        $this->favorites()->where($attributes)->get()->each->delete();
    }

    /**
     * A reply can be favorited
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favorited');
    }
}
