<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'username', 'email', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    public const ROLE_ADMIN = 'admin';

    public const ROLE_LURAH = 'lurah';

    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public function resolvedRole(): string
    {
        $role = $this->role;

        if ($role === null || $role === '') {
            return self::ROLE_ADMIN;
        }

        return $role;
    }

    public function hasRole(string ...$roles): bool
    {
        return in_array($this->resolvedRole(), $roles, true);
    }

    public function isAdmin(): bool
    {
        return $this->resolvedRole() === self::ROLE_ADMIN;
    }

    public function canAccessAdmin(): bool
    {
        return $this->hasRole(self::ROLE_ADMIN, self::ROLE_LURAH);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
