<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    // ROLE PENGGUNA

    public const ROLE_SUPER_ADMIN = 'super_admin';
    public const ROLE_EDITOR = 'editor';
    public const ROLE_USER = 'user';

    // CASTING ATRIBUT

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // HAK AKSES PANEL FILAMENT

    public function canAccessPanel(Panel $panel): bool
    {
        return $panel->getId() === 'admin'
            && $this->canManageContent();
    }

    // PEMERIKSAAN ROLE

    public function isSuperAdmin(): bool
    {
        return $this->is_active
            && $this->role === self::ROLE_SUPER_ADMIN;
    }

    public function isEditor(): bool
    {
        return $this->is_active
            && $this->role === self::ROLE_EDITOR;
    }

    public function canManageContent(): bool
    {
        return $this->isSuperAdmin()
            || $this->isEditor();
    }

    public function canManageUsers(): bool
    {
        return $this->isSuperAdmin();
    }

    // RELASI BERITA

    public function news(): HasMany
    {
        return $this->hasMany(News::class);
    }

    // RELASI PUBLIKASI

    public function publications(): HasMany
    {
        return $this->hasMany(Publication::class);
    }

    // RELASI PROJECT IT

    public function itProjects(): HasMany
    {
        return $this->hasMany(ItProject::class);
    }

    // RELASI MEDIA

    public function uploadedMedia(): HasMany
    {
        return $this->hasMany(
            Media::class,
            'uploaded_by'
        );
    }
}
