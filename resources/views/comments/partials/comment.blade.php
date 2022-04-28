<article class="shadow-md mb-10 p-4 bg-white" id="comment-{{ $item->id }}">
    <header class="mb-4 flex items-center justify-between">
        {{-- Author --}}
        <div class="text-sm">
            <a href="{{ route('users.show', $item->user) }}" class="font-bold">{{ $item->user->name }}</a>
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

            {{-- Bookmarks --}}
            <x-likes type="comments" :model="$item" class="ml-6"/>
        </div>

        {{-- Created At --}}
        <time class="text-gray-400" title="{{ $item->created_at }}">
            {{ $item->created_at->diffForHumans() }}
        </time>
    </footer>
</article>
