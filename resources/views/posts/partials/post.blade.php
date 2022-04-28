@php
$isList ??= false;
@endphp

<article class="shadow-md mb-10 p-4 bg-white">
    <header class="mb-4">
        {{-- Author --}}
        <div class="text-sm">
            <span class="text-gray-400">{{ __('Posted by') }}</span>
            <a href="{{ route('users.show', $item->author) }}" class="font-bold">{{ $item->author->name }}</a>
        </div>

        {{-- Title --}}
        <h2 class="text-xl tracking-tight font-bold dark:text-slate-200 my-2">
            @if($isList)
                <a href="{{ route('posts.show', $item) }}">{{ $item->title }}</a>
            @else
                {{ $item->title }}
            @endif
        </h2>

        {{-- Tags --}}
        <x-tags :item="$item"/>
    </header>

    <main>
        {{-- Body --}}
        <div>{{ $item->body }}</div>

        {{-- Read more --}}
        @if($isList)
            <a href="{{ route('posts.show', $item) }}" class="block mt-4">{{ __('Read more') }}</a>
        @endif
    </main>

    <footer class="flex justify-between mt-4 text-sm">
        <div class="flex">
            {{-- Votes --}}
            <x-votes type="posts" :model="$item"/>

            {{-- Views --}}
            <div class="flex items-center ml-6" title="{{ __('Views') }}">
                <x-icon.eye class="mr-2"/>
                <span>952</span>
            </div>

            {{-- Bookmarks --}}
            <x-likes type="posts" :model="$item" class="ml-6"/>

            {{-- Comments --}}
            <a href="{{ route('posts.show', $item) . '#comments' }}" class="flex items-center ml-6" title="{{ __('Comments') }}">
                <x-icon.chat class="mr-2"/>
                <span>{{ $item->comments_count }}</span>
            </a>
        </div>

        {{-- Created At --}}
        <time class="text-gray-400" title="{{ $item->created_at }}">
            {{ $item->created_at->diffForHumans() }}
        </time>
    </footer>
</article>
