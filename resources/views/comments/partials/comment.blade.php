<article class="shadow-md mb-10 p-4 bg-white" id="comment-{{ $item->id }}">
    <header class="mb-4 flex items-center justify-between">
        {{-- Author --}}
        <div class="text-sm">
            <span class="text-gray-400">{{ __('Posted by') }}</span>
            <a href="#" class="font-bold">{{ $item->author->name }}</a>
        </div>

        {{-- Actions --}}
        <div class="text-sm">
            @can('delete', $item)
                <form action="{{ route('posts.comments.destroy', [$item->post_id, $item]) }}" method="POST">
                    @method('delete')
                    @csrf

                    <button href="#" title="{{ __('Delete') }}">
                        <x-icon.trash/>
                    </button>
                </form>
            @endcan
        </div>
    </header>

    <main>
        {{-- Body --}}
        <div>{{ $item->body }}</div>
    </main>

    <footer class="flex justify-between mt-4 text-sm">
        <div class="flex">
            {{-- Votes --}}
            <x-votes type="comments" :model="$item"/>

            {{-- Likes --}}
            <x-likes type="comments" :id="$item->id" :active="$item->liked" :count="$item->likes_count" class="ml-6"/>

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
        </div>

        {{-- Created At --}}
        <div class="text-gray-400" title="{{ $item->created_at }}">
            {{ $item->created_at->diffForHumans() }}
        </div>
    </footer>
</article>
