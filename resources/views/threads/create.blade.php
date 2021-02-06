@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Create a New Thread</div>

                    <div class="card-body">

                        <form action="/threads" method="POST">
                            @csrf

                            <div class="form-group">
                                <label for="channel_id">Choose a Channel:</label>
                                <select name="channel_id" id="channel_id" class="form-control" required>
                                    <option value="">Choose One...</option>
                                    @foreach($channels as $channel)
                                        <option value="{{ $channel->id }}"
                                            {{ old('channel_id') == $channel->id ?? 'selected' }}
                                        >
                                            {{ $channel->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('channel_id')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror

                            <div class="form-group">
                                <label for="title">Title:</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="title"
                                    name="title"
                                    required
                                    value="{{ old('title') }}">
                            </div>
                            @error('title')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror

                            <div class="form-group">
                                <label for="body">Body:</label>
                                <textarea
                                    name="body"
                                    id="body"
                                    class="form-control"
                                    required
                                    rows="10">{{ old('body') }}</textarea>
                            </div>
                            @error('body')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror

                            <button type="submit" class="btn btn-primary">Publish</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
