<?php

namespace App\Http\Controllers;

use App\Inspections\Spam;
use App\Models\Reply;
use App\Models\Thread;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
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
     * @return Application|ResponseFactory|Model|Response
     */
    public function store($channelId, Thread $thread)
    {
        try {
            $this->validateReply();

            $reply = $thread->addReply([
                'body' => request('body'),
                'user_id' => auth()->id()
            ]);
        } catch (Exception $e) {
            return response('Sorry, your reply cannot be saved at this time', 422);
        }

        return $reply->load('owner');

    }

    /**
     * Update the given reply.
     *
     * @param Reply $reply
     * @throws AuthorizationException
     * @throws ValidationException
     */
    public function update(Reply $reply)
    {
        $this->authorize('update', $reply);

        try {
            $this->validateReply();
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

    /**
     * @param Spam $spam
     * @throws ValidationException
     */
    protected function validateReply()
    {
        $this->validate(request(), ['body' => 'required']);
        resolve(Spam::class)->detect(request('body'));
    }
}
