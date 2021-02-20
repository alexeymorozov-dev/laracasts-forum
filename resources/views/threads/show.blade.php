@extends('layouts.app')

@section('header')
    <link href="{{ asset('css/vendor/tribute.css') }}" rel="stylesheet">
@endsection

@section('content')
    <thread-view inline-template :initial-replies-count="{{ $thread->replies_count }}">

        <div class="container">
            <div class="row">

                <!-- Left panel -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <div class="level">

                                <img src="{{ $thread->creator->avatar_path }}"
                                     class="mr-3"
                                     width="25"
                                     height="25"
                                     alt="{{ $thread->creator->name }}">

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

                    <replies @added="repliesCount++"
                             @removed="repliesCount--">
                    </replies>

                </div>
                <!-- End of the left panel -->

                <!-- Right panel -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <p>
                                This thread was published {{ $thread->created_at->diffForHumans() }} by
                                <a href="#">{{ $thread->creator->name }}</a>, and currently has
                                <span
                                    v-text="repliesCount"></span> {{ \Str::plural('comment', $thread->replies_count) }}.
                            </p>

                            <p>
                                @auth
                                    <subscribe-button
                                        :active="{{ json_encode($thread->isSubscribedTo) }}"></subscribe-button>
                                @endauth
                            </p>


                        </div>
                    </div>
                </div>
                <!-- End of the right panel -->

            </div>
        </div>

    </thread-view>
@endsection
