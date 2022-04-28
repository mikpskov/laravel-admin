@extends('layouts.app')

@section('content')
    <div class="relative">
        @include('posts.partials.post', ['isList' => false])
    </div>

    <div id="comments">
        {{-- todo: empty view in 4th argument --}}
        @each('comments.partials.comment', $comments, 'item')

        @includeWhen(auth()->user()?->can('create', \App\Models\Comment::class), 'comments.partials.form')
    </div>
@endsection
