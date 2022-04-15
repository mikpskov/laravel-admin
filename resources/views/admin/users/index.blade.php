@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ __('Users') }}</h5>
                    {{-- <x-admin.select name="role" :options="['2' => 'admin', '3' => 'editor']"/> --}}

                    @can('users.create')
                        <a type="button" class="btn btn-primary" href="{{ route('admin.users.create') }}">{{ __('Add user') }}</a>
                    @endcan
                </div>

                <div class="card-body p-0">
                    <table class="table table-sm table-hover">
                        {{--
                        <thead>
                        <tr>
                            @foreach($headers as $header)
                                <th>{{ $header }}</th>
                            @endforeach
                            <th></th>
                        </tr>
                        </thead>
                        --}}

                        <tbody>
                        @foreach($items as $item)
                            <tr>
                                @foreach($headers as $header)
                                    <td>{{ $item->{$header} }}</td>
                                @endforeach

                                @canany(['users.update', 'users.delete'])
                                    <td class="text-end">
                                        <a
                                            href="#"
                                            class="link-secondary"
                                            type="button"
                                            id="actions-{{ $item->id }}"
                                            data-bs-toggle="dropdown"
                                            aria-expanded="false"
                                        >
                                            <i class="bi-three-dots-vertical"></i>
                                        </a>

                                        <ul class="dropdown-menu" aria-labelledby="actions-{{ $item->id }}">
                                            @can('users.update')
                                                <li>
                                                    <a
                                                        class="dropdown-item"
                                                        href="{{ route('admin.users.edit', $item) }}"
                                                    >
                                                        {{ __('Edit') }}
                                                    </a>
                                                </li>
                                            @endcan

                                            {{--
                                            @can('users.status')
                                                <li><a class="dropdown-item" href="#">{{ __('Disable') }}</a></li>
                                            @endcan
                                            --}}

                                            @can('users.delete')
                                                <li>
                                                    <form action="{{ route('admin.users.destroy', $item) }}" method="POST">
                                                        @method('DELETE')
                                                        @csrf

                                                        <button class="dropdown-item">{{ __('Delete') }}</button>
                                                    </form>
                                                </li>
                                            @endcan
                                        </ul>
                                    </td>
                                @endcanany()
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
