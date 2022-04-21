<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>App</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="max-w-5xl mx-auto px-4 pb-10 pt-10 sm:px-6 md:px-8 xl:px-12 xl:max-w-6xl text-gray-600">
@yield('content')
</body>
</html>
