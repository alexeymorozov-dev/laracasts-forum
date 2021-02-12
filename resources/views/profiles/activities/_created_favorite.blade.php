@component('profiles.activities._activity')
    @slot('heading')
        <strong>{{ $profileUser->name }}</strong>
            <a href="{{ $record->subject->favorited->path() }}">
                favorited a reply
            </a>
        {{--        <a href="{{ $record->subject->thread->path() }}">--}}
        {{--            {{ $record->subject->thread->title }}--}}
        {{--        </a>--}}
    @endslot

    @slot('body')
        {{ $record->subject->favorited->body }}
    @endslot
@endcomponent
