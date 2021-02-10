@extends('layouts.app')

@section('content')
    <div class="container">
        <div style="border-bottom: 1px solid #eee; padding-bottom: 9px; margin: 40px 0 20px;">
            <h1>
                {{ $profileUser->name }}
                <small>since {{ $profileUser->created_at->diffForHumans() }}</small>
            </h1>
        </div>

        @foreach($threads as $thread)
            <div class="card mb-4">
                <div class="card-header">
                    <div class="level">
                        <span class="flex">
                            <a href="{{ route('profile', $thread->creator) }}">
                                {{ $thread->creator->name }}
                            </a> posted: {{ $thread->title }}
                        </span>

                        <span>{{ $thread->created_at->diffForHumans() }}</span>
                    </div>
                </div>

                <div class="card-body">
                    <div class="body">
                        {{ $thread->body }}
                    </div>
                </div>
            </div>
        @endforeach

        {{ $threads->links() }}
    </div>


@endsection

