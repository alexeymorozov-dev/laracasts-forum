<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class UserAvatarController extends Controller
{
    /**
     * Save the user's avatar
     *
     * @return RedirectResponse
     */
    public function store()
    {
        request()->validate([
            'avatar' => 'required|image',
        ]);

        auth()->user()->update([
            'avatar_path' => request()
                ->file('avatar')
                ->store('avatars', 'public'),
        ]);

        return back();
    }
}
