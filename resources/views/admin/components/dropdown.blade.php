<ul class="dropdown-menu" aria-labelledby="{{ $name }}">
    @foreach ($items as $item)
        @if ($item->method === 'GET')
            <li>
                <a
                    class="dropdown-item"
                    href="{{ $item->link }}"
                >
                    {{ $item->name }}
                </a>
            </li>
        @else
            <li>
                <form action="{{ $item->link }}" method="POST">
                    @method($item->method)
                    @csrf

                    <button class="dropdown-item">{{ $item->name }}</button>
                </form>
            </li>
        @endif
    @endforeach
</ul>
