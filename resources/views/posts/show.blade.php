@extends('layouts.app')

@section('content')
    <div class="relative">
        @include('posts.partials.post', ['isList' => false])

        <a href="{{ route('posts.index') }}" class="absolute top-4 -left-28 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
            {{ __('Home') }}
        </a>
    </div>
@endsection
