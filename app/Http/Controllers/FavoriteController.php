<?php

namespace App\Http\Controllers;

use App\Models\Traits\Favorable;
use App\Models\Reply;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Store a new favorite in the database
     *
     * @param Reply $reply
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Reply $reply)
    {
        $reply->favorite();

        return back();
    }

    /**
     * Delete the favorite in the database
     *
     * @param Reply $reply
     */
    public function destroy(Reply $reply)
    {
        $reply->unfavorite();
    }
}
