@forelse($threads as $thread)
    <div class="card mb-4">
        <div class="card-header">
            <div class="level">

                <div class="flex">
                    <h4>
                        <a href="{{ $thread->path() }}">
                            @if(auth()->check() && $thread->hasUpdatesFor(auth()->user()))
                                <strong>
                                    {{ $thread->title }}
                                </strong>
                            @else
                                {{ $thread->title }}
                            @endif
                        </a>
                    </h4>

                    <h6>Posted by: <a href="{{ route('profile', $thread->creator) }}">
                            {{ $thread->creator->name }}
                        </a>
                    </h6>
                </div>

{{--                <a href="{{ $thread->path() }}">--}}
{{--                    {{ $thread->replies_count }}--}}
{{--                    {{ Str::plural('reply', $thread->replies_count) }}--}}
{{--                </a>--}}
            </div>
        </div>
        <div class="card-body">
            <div class="body">{{ $thread->body }}</div>
        </div>

        <div class="card-footer d-flex justify-content-between align-items-center">

            <span><i class="far fa-eye mr-1"></i>{{ $thread->visits()->count() }}</span>

            <a href="{{ $thread->path() }}">
                {{ $thread->replies_count }}
                {{ Str::plural('reply', $thread->replies_count) }}
            </a>

        </div>
    </div>
@empty
    <p>There are no relevant results at this time.</p>
@endforelse
