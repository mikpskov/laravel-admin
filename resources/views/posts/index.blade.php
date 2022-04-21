@extends('layouts.app')

@section('content')
    @foreach($items as $item)
        @include('posts.partials.post', ['isList' => true])
    @endforeach

    <div class="flex justify-center">
        {{--
        <a href="#" class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
            {{ __('Prev') }}
        </a>
        --}}

        {{ $items->links() }}

        {{--
        <a href="#" class="flex items-center">
            {{ __('Next') }}
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
            </svg>
        </a>
        --}}
    </div>
@endsection
