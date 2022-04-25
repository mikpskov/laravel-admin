<article class="shadow-md mb-10 p-4 bg-white">
    <header class="mb-4">
        {{-- Author --}}
        <div class="text-sm">
            <span class="text-gray-400">{{ __('Posted by') }}</span>
            <a href="#" class="font-bold">{{ $item->author->name }}</a>
        </div>

        {{-- Title --}}
        <h2 class="text-xl tracking-tight font-bold dark:text-slate-200 my-2">
            @if($isList)
                <a href="{{ route('posts.show', $item) }}">{{ $item->title }}</a>
            @else
                {{ $item->title }}
            @endif
        </h2>

        <div class="text-sm text-gray-400">
            <a href="#">main tag</a>,
            <a href="#">second</a>,
            <a href="#">more one</a>
        </div>
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

            {{-- Likes --}}
            <x-likes type="posts" :id="$item->id" :active="$item->liked" :count="$item->likes_count" class="ml-6"/>

            {{-- Views --}}
            <div class="flex items-center ml-6" title="{{ __('Views') }}">
                <x-icon.eye class="mr-2"/>
                <span>952</span>
            </div>

            {{-- Bookmarks --}}
            @guest
                <a href="{{ route('login') }}" class="flex items-center ml-6" title="{{ __('Bookmarks') }}">
                    <x-icon.bookmark class="mr-2"/>
                    <span>2</span>
                </a>
            @else
                <a href="#" class="flex items-center ml-6" title="{{ __('Bookmarks') }}">
                    <x-icon.bookmark class="mr-2"/>
                    <span>2</span>
                </a>
            @endguest

            {{-- Comments --}}
            <a href="{{ route('posts.show', $item) . '#comments' }}" class="flex items-center ml-6" title="{{ __('Comments') }}">
                <x-icon.chat class="mr-2"/>
                <span>{{ $item->comments_count }}</span>
            </a>
        </div>

        {{-- Created At --}}
        <div class="text-gray-400" title="{{ $item->created_at }}">
            {{ $item->created_at->diffForHumans() }}
        </div>
    </footer>
</article>
