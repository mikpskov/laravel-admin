@extends('layouts.app')

@section('content')
    <x-user-nav :user="$user" class="mb-4"/>

    <div id="comments">
        {{-- todo: empty view in 4th argument --}}
        @each('comments.partials.comment', $items, 'item')
    </div>

    <div class="flex justify-center">
        {{ $items->links() }}
    </div>
@endsection
