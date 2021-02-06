@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <a href="#">
                            {{ $thread->creator->name }}
                        </a> posted: {{ $thread->title }}
                    </div>

                    <div class="card-body">
                        <div class="body">
                            {{ $thread->body }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-8">
                @foreach($thread->replies as $reply)
                    @include('threads.reply')
                @endforeach
            </div>
        </div>

        @auth
            <div class="row justify-content-center">
                <div class="col-md-8">
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
                </div>
            </div>
        @endauth

        @guest
            <p class="text-center mt-4">Please <a href="{{ route('login') }}">sign in</a> to participate in this discussion.</p>
        @endguest


    </div>
@endsection
