@extends('layouts.app')

@section('content')
    <x-user-nav :user="$user" class="mb-4"/>

    <article class="shadow-md mb-10 p-4 bg-white">
        <h2 class="text-xl tracking-tight font-bold dark:text-slate-200 my-2">
            {{ $user->name }}
        </h2>
    </article>
@endsection
