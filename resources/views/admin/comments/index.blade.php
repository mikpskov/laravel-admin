@extends('admin.layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <header class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ $title }}</h5>

                    <div id="filters" class="row">
                        {{-- Search input --}}
                        <div class="col">
                            <form method="get">
                                <x-admin.input name="search" placeholder="{{ __('Search') }}" :value="$search"></x-admin.input>
                            </form>
                        </div>

                        {{-- Approved select --}}
                        <div class="col">
                            <x-admin.select
                                name="filter[approved]"
                                :options="['-1' => 'all', 0 => 'unapproved', 1 => 'approved']"
                                :selected="$approvedFilter"
                            ></x-admin.select>
                        </div>
                    </div>
                </header>

                <main class="card-body p-0">
                    <table class="table table-sm table-actions">
                        <tbody>
                        @foreach($items as $item)
                            <tr @if(!$item->isApproved()) class="table-secondary" @endif>
                                <td>{{ $item->id }}</td>

                                {{-- User name --}}
                                <td>
                                    <a
                                        href="{{ route('users.show', $item->user_id) }}"
                                        class="text-decoration-none"
                                    >
                                        {{ $item->user->name }}
                                    </a>
                                </td>

                                {{-- Post title --}}
                                <td>
                                    <a
                                        href="{{ route('posts.show', ['post' => $item->post_id]) . "#comment-{$item->id}" }}"
                                        class="text-decoration-none"
                                    >
                                        {{ $item->post->title }}
                                    </a>
                                </td>

                                {{-- Actions --}}
                                <td class="text-end actions-column">
                                    @canany(['approve', 'delete'], $item)
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
                </main>

                <footer class="card-footer d-flex justify-content-between align-items-center">
                    <div>
                        <x-admin.per-page name="comments_perPage" :items="config('pagination.per_page.elements')" :selected="$perPage"/>
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
    document
        .getElementById('filters')
        .querySelector('select[name="filter[approved]"]')
        .addEventListener('change', function() {
            window.location.replace(
                "{{ route('admin.comments.index') }}" + (this.value >= 0 ? `?approved=${this.value}` : '')
            )
        })
})()
</script>
@endpush
