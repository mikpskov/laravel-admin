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
                    <x-icon.heart class="mr-2"/>
                    <span>55</span>
                </span>
            @else
                <a href="#" class="flex items-center">
                    <x-icon.heart class="mr-2"/>
                    <span>55</span>
                </a>
            @endguest

            {{-- Views --}}
            <div class="flex items-center ml-4">
                <x-icon.eye class="mr-2"/>
                <span>952</span>
            </div>

            {{-- Bookmarks --}}
            @guest
                <span class="flex items-center ml-4">
                    <x-icon.bookmark class="mr-2"/>
                    <span>2</span>
                </span>
            @else
                <a href="#" class="flex items-center ml-4">
                    <x-icon.bookmark class="mr-2"/>
                    <span>2</span>
                </a>
            @endguest

            {{-- Comments --}}
            <a href="{{ route('posts.show', $item) . '#comments' }}" class="flex items-center ml-4">
                <x-icon.chat class="mr-2"/>
                <span>56</span>
            </a>
        </div>

        {{-- Created At --}}
        <div class="text-gray-400">
            {{ $item->created_at }}
        </div>
    </footer>
</article>
