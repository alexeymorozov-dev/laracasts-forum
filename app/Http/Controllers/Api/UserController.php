<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Collection;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Collection
     */
    public function index()
    {
        $search = request('name');

        $val = User::where('name', 'LIKE', "$search%")
            ->take(5)
            ->pluck('name')
            ->map(function ($name) {
                return ['value' => $name];
            });

        return $val;
    }
}
