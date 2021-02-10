@component('profiles.activities._activity')
    @slot('heading')
        {{ $profileUser->name }} published a
        <a href="{{ $record->subject->path() }}">
            {{ $record->subject->title }}
        </a>
    @endslot

    @slot('body')
        {{ $record->subject->body }}
    @endslot
@endcomponent


