<div id="reply_{{ $reply->id }}" class="card mt-3">
    <div class="card-header">
        <div class="level">
            <h5 class="flex">
                <a href="{{ route('profile', $reply->owner) }}">
                    {{ $reply->owner->name }}
                </a> said {{ $reply->created_at->diffForHumans() }}...
            </h5>

            <div>
                <form method="POST" action="/reply/{{ $reply->id }}/favorites">
                    @csrf

                    <button type="submit" class="btn btn-primary"
                            @if($reply->isFavorited()) disabled @endif
                    >
                        {{ $reply->favorites_count }}
                        {{ \Str::plural('Favorite', $reply->favorites_count) }}
                    </button>
                </form>
            </div>

        </div>
    </div>

    <div class="card-body">
        <div class="body">
            {{ $reply->body }}
        </div>
    </div>

    @can ('update', $reply)
        <div class="card-footer">
            <form method="POST" action="/replies/{{ $reply->id }}">
                @csrf
                @method('DELETE')

                <button class="btn btn-danger btn-sm" type="submit">Delete</button>
            </form>
        </div>
    @endcan

</div>
