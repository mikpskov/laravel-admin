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

                        {{-- Order select --}}
                        <div class="col">
                            <x-admin.order :orders="[
                                'id' => __('ID'),
                                'name' => __('Name'),
                                'posts_count' => __('Posts count'),
                                'comments_count' => __('Comments count'),
                            ]" :selected="$order"/>
                        </div>
                    </div>

                    {{-- Add button --}}
                    @can('create', \App\Models\User::class)
                        <a type="button" class="btn btn-primary" href="{{ $createUrl }}">{{ __('Add') }}</a>
                    @endcan
                </header>

                <main class="card-body p-0">
                    <table class="table table-sm table-actions">
                        <tbody>
                        @foreach($items as $item)
                            <tr>
                                <td>{{ $item->id }}</td>

                                <td>
                                    <a
                                        href="{{ route('users.show', $item->id) }}"
                                        class="text-decoration-none"
                                    >
                                        {{ $item->name }}
                                    </a>
                                </td>

                                <td>{{ $item->email }}</td>
                                <td><span title="{{ $item->created_at }}">{{ $item->created_at?->diffForHumans() }}</span></td>

                                {{-- Posts count --}}
                                <td>
                                    <a
                                        href="{{ route('admin.posts.index', ['user' => $item->id]) }}"
                                        title="{{ __('Posts') }}"
                                        class="link-secondary text-decoration-none"
                                    >
                                        <i class="bi bi-file-text"></i>
                                        {{ $item->posts_count }}
                                    </a>
                                </td>

                                {{-- Comments count --}}
                                <td>
                                    <a
                                        href="{{ route('admin.comments.index', ['user' => $item->id]) }}"
                                        title="{{ __('Comments') }}"
                                        class="link-secondary text-decoration-none"
                                    >
                                        <i class="bi bi-chat-dots"></i>
                                        {{ $item->comments_count }}
                                    </a>
                                </td>

                                {{-- Actions --}}
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
                        <x-admin.per-page name="users_perPage" :items="config('pagination.per_page.elements')" :selected="$perPage"/>
                    </div>

                    {{ $items->links() }}
                </footer>
            </div>
        </div>
    </div>
</div>
@endsection
