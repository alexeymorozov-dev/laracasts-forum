<?php

namespace App\Models\Traits;

use Illuminate\Support\Facades\Redis;

trait RecordsVisits
{

    public function visits()
    {
        return Redis::get($this->cacheKey()) ?? 0;
    }

    public function cacheKey()
    {
        return "threads.{$this->id}.visits";
    }

    public function recordVisit()
    {
        Redis::incr($this->cacheKey());

        return $this;
    }

    public function resetVisits()
    {
        Redis::del($this->cacheKey());

        return $this;
    }
}
