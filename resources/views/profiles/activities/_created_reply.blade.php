@component('profiles.activities._activity')
    @slot('heading')
        {{ $profileUser->name }} replied to
        <a href="{{ $record->subject->thread->path() }}">
            {{ $record->subject->thread->title }}
        </a>
    @endslot

    @slot('body')
        {{ $record->subject->body }}
    @endslot
@endcomponent
