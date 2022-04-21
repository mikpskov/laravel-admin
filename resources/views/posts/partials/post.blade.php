<article class="shadow-md mb-10 p-4">
    <header class="mb-4">
        <h2 class="text-xl tracking-tight font-bold dark:text-slate-200">
            @if($isList)
                <a href="{{ route('posts.show', $item) }}">{{ $item->title }}</a>
            @else
                {{ $item->title }}
            @endif
        </h2>
        <a href="#" class="font-bold text-sm text-gray-400">{{ $item->author->name }}</a>
    </header>

    <main>
        <div>{{ $item->body }}</div>
        @if($isList)
            <a href="{{ route('posts.show', $item) }}" class="block mt-4">{{ __('Read more') }}</a>
        @endif
    </main>

    <footer class="flex justify-between mt-4">
        <div class="flex">
            {{-- Likes --}}
            @guest
                <span class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                    <span>55</span>
                </span>
            @else
                <a href="#" class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                    <span>55</span>
                </a>
            @endguest

            {{-- Views --}}
            <div class="flex items-center ml-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-4.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                <span>952</span>
            </div>

            {{-- Bookmarks --}}
            @guest
                <span class="flex items-center ml-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                    </svg>
                    <span>2</span>
                </span>
            @else
                <a href="#" class="flex items-center ml-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                    </svg>
                    <span>2</span>
                </a>
            @endguest

            {{-- Comments --}}
            <a href="{{ route('posts.show', $item) . '#comments' }}" class="flex items-center ml-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
                <span>56</span>
            </a>
        </div>

        {{-- Created At --}}
        <div class="text-gray-400">
            {{ $item->created_at }}
        </div>
    </footer>
</article>
