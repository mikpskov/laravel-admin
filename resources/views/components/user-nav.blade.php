<nav {{ $attributes }}>
    <ul class="flex gap-x-4">
        @foreach($items as $item)
            <li>
                <a
                    href="{{ $item->link }}"
                    class="block rounded-md px-4 py-2 {{ $isActive($item) ? 'bg-white shadow-md' : 'bg-gray-200' }}"
                >
                    {{ $item->name }}
                </a>
            </li>
        @endforeach
    </ul>
</nav>
