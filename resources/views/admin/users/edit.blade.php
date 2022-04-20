@extends('admin.layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <form method="POST" action="{{ $user === null ? route('admin.users.store') : route('admin.users.update', $user) }}">
                    @method($user === null ? 'POST' : 'PUT')
                    @csrf

                    <header class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ $user === null ? __('Create user') : __('Edit user') }}</h5>
                    </header>

                    <main class="card-body">
                        <div class="mb-3">
                            <x-admin.input name="name" label="{{ __('Name') }}" :model="$user"/>
                        </div>

                        <div class="mb-3">
                            <x-admin.input name="email" label="{{ __('Email') }}" :model="$user"/>
                        </div>

                        <div class="mb-3">
                            <x-admin.input name="password" label="{{ __('Password') }}" type="password"/>
                        </div>

                        <div class="mb-3">
                            <x-admin.select
                                name="role"
                                label="{{ __('Role') }}"
                                :options="$roles"
                                :selected="$user?->getRole()?->id"
                                empty="{{ __('No role') }}"
                            />
                        </div>

                        <div class="permissions-block">
                            <x-admin.checkbox-group
                                name="permissions"
                                label="{{ __('Permissions') }}"
                                :items="$permissions"
                                :selected="$user?->permissions->pluck('id')"
                            />
                        </div>
                    </main>

                    <footer class="card-footer text-end">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">{{ __('Close') }}</a>
                        <button class="btn btn-primary">{{ __('Save') }}</button>
                    </footer>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(() => {
    const $select = document.getElementById('role');

    $select.addEventListener('change', function() {
        document.querySelector(".permissions-block").style.display = this.value ? '' : 'none';
    });

    $select.dispatchEvent(new Event('change'));
})()
</script>
@endpush
