<?php

namespace App\Filters;

use App\Models\User;
use Illuminate\Http\Request;

class ThreadFilters extends Filters
{
    protected $filters = ['by'];
    /**
     * Filter a query by a given username
     *
     * @param $username
     * @return mixed
     */
    protected function by($username)
    {
        $user = User::where('name', $username)->firstOrFail();

        return $this->builder->where('user_id', $user->id);
    }
}
