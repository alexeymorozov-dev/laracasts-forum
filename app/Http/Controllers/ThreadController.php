<?php

namespace App\Http\Controllers;

use App\Filters\ThreadFilters;
use App\Models\Channel;
use App\Models\Thread;
use App\Models\Trending;
use App\Rules\SpamFree;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Redis;

class ThreadController extends Controller
{
    /**
     * Create a new ThreadController instance.
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Channel       $channel
     * @param ThreadFilters $filters
     * @param Trending      $trending
     * @return Application|LengthAwarePaginator|Factory|View
     */
    public function index(Channel $channel, ThreadFilters $filters, Trending $trending)
    {
        $threads = Thread::getThreads($filters, $channel);

        if (\request()->wantsJson()) {
            return $threads;
        }

        return view('threads.index', [
            'threads' => $threads,
            'trending' => $trending->get()
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param          $channel
     * @param Thread   $thread
     * @param Trending $trending
     * @return Application|Factory|View
     */
    public function show($channel, Thread $thread, Trending $trending)
    {
        if (auth()->check()) {
            auth()->user()->read($thread);
        }

        $trending->push($thread);

        $thread->recordVisit();

        return view('threads.show', compact('thread'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('threads.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Application|RedirectResponse|Redirector
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', new SpamFree],
            'body' => ['required', new SpamFree],
            'channel_id' => 'required|exists:channels,id',
        ]);

        $thread = Thread::create([
            'user_id' => auth()->id(),
            'channel_id' => $request['channel_id'],
            'title' => $request['title'],
            'body' => $request['body'],

        ]);

        return redirect($thread->path())
            ->with('flash', 'The thread has been published.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param        $channel
     * @param Thread $thread
     * @return Application|Redirector|RedirectResponse
     * @throws AuthorizationException
     */
    public function destroy($channel, Thread $thread)
    {
        $this->authorize('update', $thread);

        $thread->delete();

        if (\request()->wantsJson()) {
            return \response([], 204);
        }

        return redirect('/threads');
    }
}
