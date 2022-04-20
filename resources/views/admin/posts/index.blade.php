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

                    @can('create', \App\Models\Post::class)
                        <a type="button" class="btn btn-primary" href="{{ $createUrl }}">{{ __('Add') }}</a>
                    @endcan
                </div>

                <div class="card-body p-0">
                    <table class="table table-sm table-actions">
                        <tbody>
                        @foreach($items as $item)
                            <tr @if(!$item->isPublished()) class="table-secondary" @endif>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->author->name }}</td>
                                <td>{{ $item->title }}</td>

                                <td class="text-end actions-column">
                                    @canany(['update', 'publish', 'delete'], $item)
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

                                        <x-admin.dropdown name="actions-{{ $item->id }}" :items="$item->getActions()"/>
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
        document.cookie = `posts_perPage=${this.value}`;
        window.location.replace("{{ route('admin.posts.index') }}");
    });
})()
</script>
@endpush
