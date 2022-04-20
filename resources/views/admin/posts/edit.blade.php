@extends('admin.layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <form method="POST" action="{{ $item === null ? route('admin.posts.store') : route('admin.posts.update', $item) }}">
                    @method($item === null ? 'POST' : 'PUT')
                    @csrf

                    <header class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ $item === null ? __('Create post') : __('Edit post') }}</h5>
                    </header>

                    <main class="card-body">
                        <div class="mb-3">
                            <x-admin.input name="title" label="{{ __('Title') }}" :model="$item"/>
                        </div>

                        <div class="mb-3">
                            <x-admin.textarea name="body" label="{{ __('Body') }}" :model="$item" rows="10"/>
                        </div>
                    </main>

                    <footer class="card-footer text-end">
                        <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">{{ __('Close') }}</a>
                        <button class="btn btn-primary">{{ __('Save') }}</button>
                    </footer>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
