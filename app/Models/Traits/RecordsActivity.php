<?php


namespace App\Models\Traits;


use Illuminate\Database\Eloquent\Relations\MorphMany;
use ReflectionClass;

trait RecordsActivity
{

    /**
     * Boot the trait.
     */
    protected static function bootRecordsActivity()
    {

        if (auth()->guest()) return;

        foreach (static::getActivitiesToRecord() as $event) {
            static::$event(function ($model) use ($event) {
                $model->recordActivity($event);
            });
        }

        static::deleting(function ($model) {
            $model->activity()->delete();
        });
    }

    /**
     * Fetch all model events that require activity recording.
     *
     * @return array
     */
    protected static function getActivitiesToRecord()
    {
        return ['created'];
    }

    /**
     * Record new activity for the model.
     *
     * @param string $event
     */
    public function recordActivity($event)
    {
        if (auth()->guest()) return;

        $this->activity()->create([
            'user_id' => auth()->id(),
            'type' => $this->getActivityType($event),
        ]);
    }

    /**
     * Fetch the activity relationship.
     *
     * @return MorphMany
     */
    public function activity()
    {
        return $this->morphMany('App\Models\Activity', 'subject');
    }

    /**
     * Determine the activity type.
     *
     * @param  string $event
     * @return string
     */
    protected function getActivityType($event)
    {
        $type = strtolower((new ReflectionClass($this))->getShortName());

        return "{$event}_{$type}";
    }
}
