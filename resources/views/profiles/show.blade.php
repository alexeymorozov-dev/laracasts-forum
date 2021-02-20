@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="page-header">
                    <avatar-form :user="{{ $profileUser }}"></avatar-form>
{{--                    {{ dd($profileUser) }}--}}
                </div>

                @forelse($activities as $date => $activity)
                    <h3 class="page-header">
                        {{ $date }}
                    </h3>
                    @foreach($activity as $record)
                        @if (view()->exists("profiles.activities._{$record->type}"))
                            @include("profiles.activities._{$record->type}")
                        @endif
                    @endforeach
                @empty
                    <p>There is no activity for this user yet...</p>
                    <p><a href="{{ url()->previous() }}">Return back.</a></p>
                @endforelse

            </div>
        </div>
    </div>
@endsection

