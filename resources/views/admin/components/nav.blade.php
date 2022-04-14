<ul class="navbar-nav me-auto">
    @foreach($items as $item)
        <li class="nav-item">
            <a class="nav-link{{ $isActive($item) ? ' active' : '' }}" href="{{ $item['link'] }}">{{ $item['name'] }}</a>
        </li>
    @endforeach
</ul>
