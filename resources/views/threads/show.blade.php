@extends('layouts.app')

@section('content')
    <thread-view inline-template :initial-replies-count="{{ $thread->replies_count }}">

        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <div class="level">
                            <span class="flex">
                                    <a href="{{ route('profile', $thread->creator) }}">
                                {{ $thread->creator->name }} </a> posted: {{ $thread->title }}
                            </span>

                                <form action="{{ $thread->path() }}" method="POST">
                                    @csrf
                                    @method('DELETE')

                                    @can ('update', $thread)
                                        <button type="submit" class="btn btn-link">Delete Thread</button>
                                    @endcan
                                </form>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="body">
                                {{ $thread->body }}
                            </div>
                        </div>
                    </div>

                    <hr>
                    <div class="page-heading text-xl-center">Comments:</div>

                    <replies :data="{{ $thread->replies }}"
                             @removed="repliesCount--">
                    </replies>

                    {{--                <div class="mt-4">{{ $replies->links() }}</div>--}}

                    @auth
                        <form action="{{ $thread->path() . '/replies' }}" method="POST">
                            @csrf
                            <div class="form-group mt-4">
                            <textarea
                                name="body"
                                id="body"
                                class="form-control"
                                rows="5"
                                placeholder="Have something to say?"></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary">Post</button>

                        </form>
                    @endauth

                    @guest
                        <p class="text-center mt-4">
                            Please <a href="{{ route('login') }}">sign in</a> to participate in this
                            discussion.
                        </p>
                    @endguest
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <p>
                                This thread was published {{ $thread->created_at->diffForHumans() }} by
                                <a href="#">{{ $thread->creator->name }}</a>, and currently has
                                <span
                                    v-text="repliesCount"></span> {{ \Str::plural('comment', $thread->replies_count) }}.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </thread-view>
@endsection
