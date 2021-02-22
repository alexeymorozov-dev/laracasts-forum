@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @include('threads._list')

                {{ $threads->render() }}
            </div>

            <div class="col-md-4">
                @if(count($mostViewedThreads))
                    <div class="card">

                        <div class="card-header">
                            Most viewed threads
                        </div>

                        <div class="card-body">
                            <ul class="list-group">
                                @foreach($mostViewedThreads as $thread)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <a href="{{ $thread->path() }}">
                                            {{ $thread->title }}
                                        </a>
                                        <span class="badge badge-primary badge-pill">{{ $thread->visits }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
