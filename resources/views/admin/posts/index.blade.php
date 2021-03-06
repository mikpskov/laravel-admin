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
                                <x-admin.input name="search" placeholder="{{ __('Search') }}" :value="$search"/>
                            </form>
                        </div>

                        {{-- Order select --}}
                        <div class="col">
                            <x-admin.order :orders="[
                                'id' => __('ID'),
                                'title' => __('Title'),
                                'created_at' => __('Create date'),
                                'comments_count' => __('Comments count'),
                            ]" :selected="$order"/>
                        </div>
                    </div>

                    {{-- Add button --}}
                    @can('create', \App\Models\Post::class)
                        <a type="button" class="btn btn-primary" href="{{ $createUrl }}">{{ __('Add') }}</a>
                    @endcan
                </header>

                <main class="card-body p-0">
                    <table class="table table-sm table-actions">
                        <tbody>
                        @foreach($items as $item)
                            <tr @if(!$item->isPublished()) class="table-secondary" @endif>
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
                                        href="{{ route('posts.show', $item) }}"
                                        class="text-decoration-none"
                                    >
                                        {{ $item->title }}
                                    </a>
                                </td>

                                {{-- Comments count --}}
                                <td>
                                    <a
                                        href="{{ route('admin.comments.index', ['post' => $item->id]) }}"
                                        title="{{ __('Comments') }}"
                                        class="link-secondary text-decoration-none"
                                    >
                                        <i class="bi bi-chat-dots"></i>
                                        {{ $item->comments_count ?: 0 }}
                                    </a>
                                </td>

                                {{-- Bookmarks count --}}
                                <td>
                                    <span
                                        title="{{ __('Bookmarks') }}"
                                        class="link-secondary text-decoration-none"
                                    >
                                        <i class="bi bi-bookmarks"></i>
                                        {{ $item->likes_count ?: 0 }}
                                    </span>
                                </td>

                                {{-- Votes count --}}
                                <td>
                                    <span
                                        title="{{ __('Votes') }}"
                                        class="link-secondary text-decoration-none"
                                    >
                                        <i class="bi bi-star"></i>
                                        {{ $item->votes_up_count - $item->votes_down_count }}
                                    </span>
                                </td>

                                {{-- Actions --}}
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
                </main>

                <footer class="card-footer d-flex justify-content-between align-items-center">
                    <div>
                        <x-admin.per-page name="posts_perPage" :items="config('pagination.per_page.elements')" :selected="$perPage"/>
                    </div>

                    {{ $items->links() }}
                </footer>
            </div>
        </div>
    </div>
</div>
@endsection
