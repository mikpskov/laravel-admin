<?php

namespace App\Models;

use App\View\DropdownItem;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property int $id
 * @property Collection $roles
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The number of models to return for pagination.
     *
     * @var int
     */
    protected $perPage = 20;

    protected function password(): Attribute
    {
        return Attribute::make(
            set: fn($value) => Hash::make($value),
        );
    }

    public function getRole(): ?Role
    {
        return $this->roles->isEmpty() ? null : $this->roles[0];
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function getActions(): array
    {
        /** @var User|null $user */
        $user = auth()->user();

        return [
            new DropdownItem(
                __('Edit'),
                route('admin.users.edit', $this),
                $user?->can('update', $this),
            ),
            new DropdownItem(
                __('Delete'),
                route('admin.users.destroy', $this),
                $user?->can('delete', $this),
                'DELETE',
            ),
        ];
    }
}
