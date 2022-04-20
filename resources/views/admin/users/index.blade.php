@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ $title }}</h5>

                    <form method="get">
                        <x-admin.input name="search" placeholder="{{ __('Search') }}" :value="$search"></x-admin.input>
                    </form>

                    @can('create', \App\Models\User::class)
                        <a type="button" class="btn btn-primary" href="{{ $createUrl }}">{{ __('Add') }}</a>
                    @endcan
                </div>

                <div class="card-body p-0">
                    <table class="table table-sm table-actions">
                        <tbody>
                        @foreach($items as $item)
                            <tr>
                                @foreach($headers as $header)
                                    <td>{{ $item->{$header} }}</td>
                                @endforeach

                                <td class="text-end actions-column">
                                    @canany(['update', 'delete'], $item)
                                        <a
                                            href="#"
                                            class="link-secondary actions-button"
                                            type="button"
                                            id="actions-{{ $item->id }}"
                                            data-bs-toggle="dropdown"
                                            aria-expanded="false"
                                        >
                                            <i class="bi-three-dots-vertical"></i>
                                        </a>

                                        <ul class="dropdown-menu" aria-labelledby="actions-{{ $item->id }}">
                                            @can('update', $item)
                                                <li>
                                                    <a
                                                        class="dropdown-item"
                                                        href="{{ $item->getEditLink() }}"
                                                    >
                                                        {{ __('Edit') }}
                                                    </a>
                                                </li>
                                            @endcan

                                            @can('delete', $item)
                                                <li>
                                                    <form action="{{ $item->getRemoveLink() }}" method="POST">
                                                        @method('DELETE')
                                                        @csrf

                                                        <button class="dropdown-item">{{ __('Delete') }}</button>
                                                    </form>
                                                </li>
                                            @endcan
                                        </ul>
                                    @endcanany
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <footer class="card-footer d-flex justify-content-between align-items-center">
                    <div>
                        <x-admin.select
                            name="perPage"
                            :options="[20 => 20, 50 => 50, 100 => 100]"
                            selected="{{ $perPage }}"
                        />
                    </div>

                    {{ $items->links() }}
                </footer>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(() => {
    document.getElementById('perPage').addEventListener('change', function() {
        document.cookie = `users_perPage=${this.value}`;
        window.location.replace("{{ route('admin.users.index') }}");
    });
})()
</script>
@endpush
