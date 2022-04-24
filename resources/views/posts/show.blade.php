@extends('layouts.app')

@section('content')
    <div class="relative">
        @include('posts.partials.post', ['isList' => false])

        <a href="{{ route('posts.index') }}" class="fixed top-0 left-0 px-2 py-4 block flex items-center">
            <x-icon.chevron-left class="mr-2"/>
            {{ __('Home') }}
        </a>
    </div>
@endsection
