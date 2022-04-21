@extends('layouts.app')

@section('content')
    <div class="relative">
        @include('posts.partials.post', ['isList' => false])

        <a href="{{ route('posts.index') }}" class="absolute top-4 -left-28 flex items-center">
            <x-icon.chevron-left class="mr-2"/>
            {{ __('Home') }}
        </a>
    </div>
@endsection
