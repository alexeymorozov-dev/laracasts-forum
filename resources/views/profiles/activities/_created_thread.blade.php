@component('profiles.activities._activity')
    @slot('heading')
        <strong>{{ $profileUser->name }}</strong>
        <a href="{{ $record->subject->path() }}">
            published a {{ $record->subject->title }}
        </a>
    @endslot

    @slot('body')
        {{ $record->subject->body }}
    @endslot
@endcomponent


