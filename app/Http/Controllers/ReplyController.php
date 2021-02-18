<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePostRequest;
use App\Models\Reply;
use App\Models\Thread;
use App\Models\User;
use App\Notifications\YouWereMentioned;
use App\Rules\SpamFree;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use function request;

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
     * @param CreatePostRequest $form
     * @return Model
     */
    public function store($channelId, Thread $thread, CreatePostRequest $form)
    {
        $reply = $thread->addReply([
            'body' => request('body'),
            'user_id' => auth()->id()
        ]);

        preg_match_all('/\@([^\s\.]+)/', $reply->body, $matches);

        foreach ($matches[1] as $name) {
            $user = User::whereName($name)->first();

            if ($user) {
                $user->notify(new YouWereMentioned($reply));
            }
        }

        return $reply->load('owner');
    }

    /**
     * Update the given reply.
     *
     * @param Reply $reply
     * @return Application|ResponseFactory|Response
     * @throws AuthorizationException
     */
    public function update(Reply $reply)
    {
        $this->authorize('update', $reply);

        try {
            request()->validate(['body' => ['required', new SpamFree]]);
            $reply->update(request(['body']));
        } catch (Exception $e) {
            return response(
                'Sorry, your reply cannot be saved at this time', 422
            );
        }
    }

    /**
     * Delete the given reply.
     *
     * @param Reply $reply
     * @return void
     * @throws AuthorizationException
     */
    public function destroy(Reply $reply)
    {
        $this->authorize('update', $reply);

        $reply->delete();
    }

}
