<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class UserAvatarController extends Controller
{
    /**
     * Save the user's avatar
     *
     * @return Application|Response|ResponseFactory
     */
    public function store()
    {
        request()->validate([
            'avatar' => 'required|image',
        ]);

        auth()->user()->update([
            'avatar_path' =>
                request()
                ->file('avatar')
                ->store('images/avatars', 'public'),
        ]);

        return response([], 204);
    }
}
