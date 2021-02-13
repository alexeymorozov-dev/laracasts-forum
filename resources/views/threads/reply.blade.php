<reply :attributes="{{ $reply }}" inline-template>

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
            <div v-if="editing">
                <div class="form-group">
                    <textarea class="form-control" v-model="body"></textarea>
                </div>

                <button class="btn btn-primary btn-sm" @click="update">Update</button>
                <button class="btn btn-link btn-sm" @click="editing = false">Cancel</button>

            </div>

            <div class="body" v-else v-text="body"></div>
        </div>

        @can ('update', $reply)
            <div class="card-footer level">
                <button class="btn btn-secondary btn-sm mr-2" @click="editing = true">Edit</button>
                <button class="btn btn-danger btn-sm mr-2" @click="destroy">Delete</button>
            </div>
        @endcan

    </div>

</reply>
