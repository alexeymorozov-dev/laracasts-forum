<?php

namespace App\Http\Controllers;

use App\Models\Reply;
use App\Models\Thread;
use Illuminate\Http\Request;

class ReplyController extends Controller
{
    /**
     * ReplyController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth')
            ->except('index');
    }

    public function index($channel, Thread $thread)
    {
        return $thread->replies()->paginate(20);
    }


    /**
     * Store the given reply.
     *
     * @param $channelId
     * @param Thread $thread
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store($channelId, Thread $thread)
    {
        $this->validate(request(), ['body' => 'required']);

        $reply = $thread->addReply([
            'body' => \request('body'),
            'user_id' => auth()->id()
        ]);

        if (\request()->expectsJson()) {
            return $reply->load('owner');
        }

        return back();
    }

    /**
     * Update the given reply.
     *
     * @param \App\Models\Reply $reply
     */
    public function update(Reply $reply)
    {
        $this->authorize('update', $reply);

        $this->validate(\request(), ['body' => 'required']);

        $reply->update(request(['body']));
    }

    /**
     * Delete the given reply.
     *
     * @param Reply $reply
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Reply $reply)
    {
        $this->authorize('update', $reply);

        $reply->delete();
    }
}
