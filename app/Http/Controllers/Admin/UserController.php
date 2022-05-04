<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

final class UserController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(User::class, 'user');
    }

    public function index(Request $request): View
    {
        $users = User::query()
            ->withCount(['posts', 'comments']);

        if ($orders = $request->get('orders')) {
            foreach ($orders as $column => $direction) {
                if ($column === array_key_first($orders)) {
                    $order = "{$column}_{$direction}";
                }

                $users->orderBy($column, $direction);
            }
        }

        if ($search = $request->get('search')) {
            $users->where('name', 'like', "%$search%");
            $users->orWhere('email', 'like', "%$search%");
        }

        $perPage = $request->cookie('users_perPage');

        return view('admin.users.index', [
            'items' => $users->paginate($perPage)->appends($request->except('page')),
            'perPage' => $perPage ?? config('pagination.per_page.default'),
            'search' => $search ?? '',
            'order' => $order ?? '',
            'title' => __('Users'),
            'createUrl' => route('admin.users.create'),
        ]);
    }

    public function create(): View
    {
        return view('admin.users.edit', [
            'user' => null,
            'roles' => Role::all(['id', 'name'])->pluck('name', 'id'),
            'permissions' => Permission::all(['id', 'name'])->pluck('name', 'id'),
        ]);
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $user = new User($request->validated());

        $user->syncPermissions(array_keys($request->get('permissions') ?: []));

        if ($request->has('role')) {
            $user->syncRoles($request->get('role'));
        }

        $user->save();

        return redirect()->route('admin.users.index');
    }

    public function show(User $user): never
    {
        abort(404);
    }

    public function edit(User $user): View
    {
        return view('admin.users.edit', [
            'user' => $user,
            'roles' => Role::all(['id', 'name'])->pluck('name', 'id'),
            'permissions' => Permission::all(['id', 'name'])->pluck('name', 'id'),
        ]);
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $user->update($request->validated());

        $user->syncPermissions(array_keys($request->get('permissions') ?: []));

        if ($request->has('role')) {
            $user->syncRoles($request->get('role'));
        }

        return redirect()->route('admin.users.index');
    }

    public function destroy(User $user): RedirectResponse
    {
        $user->delete();

        return redirect()->back();
    }
}
