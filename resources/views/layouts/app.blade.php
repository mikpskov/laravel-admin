<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>App</title>
    <script src="{{ asset('js/app.js') }}" defer></script>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="max-w-5xl mx-auto px-4 pb-10 pt-10 sm:px-6 md:px-8 xl:px-12 xl:max-w-6xl text-gray-600 bg-gray-100 dark:bg-gray-900">
@yield('content')

@if (Route::has('login'))
    <div class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
        @auth
            <a href="{{ route('admin.posts.index') }}" class="text-gray-700 dark:text-gray-500">{{ __('Admin') }}</a>
        @else
            <a href="{{ route('login') }}" class="text-gray-700 dark:text-gray-500">{{ __('Log in') }}</a>

            @if (Route::has('register'))
                <a href="{{ route('register') }}" class="ml-4 text-gray-700 dark:text-gray-500">{{ __('Register') }}</a>
            @endif
        @endauth
    </div>
@endif

@stack('scripts')
</body>
</html>
