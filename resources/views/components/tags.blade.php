<div {{ $attributes->merge(['class' => 'text-sm text-gray-400']) }}>
    <ul class="flex gap-x-2">
        @foreach($item->tags as $tag)
            <li>
                <a href="{{ route('posts.index', ['tag' => $tag->slug]) }}" class="block rounded-md px-2 bg-gray-200">
                    {{ $tag->name }}
                </a>
            </li>
        @endforeach
    </ul>
</div>
