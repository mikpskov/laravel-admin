<div {{ $attributes->merge(['class' => '']) }}>
    <ul class="flex gap-x-2">
        @foreach($item->tags as $tag)
            <li>
                <a href="{{ route('posts.index', ['tag' => $tag->slug]) }}" class="block rounded-md text-sm text-gray-400 bg-gray-200 px-2">
                    {{ $tag->name }}
                </a>
            </li>
        @endforeach
    </ul>
</div>
