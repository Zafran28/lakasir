<?php

namespace App\Models\Tenants;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser, HasAvatar, HasName
{
    use HasApiTokens,
        HasFactory,
        HasRoles,
        Notifiable,
        SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'fcm_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'fcm_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | FILAMENT ACCESS CONTROL (FIX LOGIN ISSUE)
    |--------------------------------------------------------------------------
    */

    public function canAccessPanel(Panel $panel): bool
    {
        // FIX: sementara dibuat aman agar tidak lock login
        if (app()->environment('local')) {
            return true;
        }

        return $this->hasAnyRole([
            'Admin',
            'Manager',
            'Cashier',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }

    public function secureInitialPrice(): HasOne
    {
        return $this->hasOne(SecureInitialPrice::class);
    }

    public function cashDrawer(): HasOne
    {
        return $this->hasOne(CashDrawer::class);
    }

    public function sellings(): HasMany
    {
        return $this->hasMany(Selling::class);
    }

    /*
    |--------------------------------------------------------------------------
    | FILAMENT DISPLAY
    |--------------------------------------------------------------------------
    */

    public function getFilamentName(): string
    {
        return $this->name ?? '';
    }

    public function getFullNameAttribute(): string
    {
        return $this->name ?? '';
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->profile?->photo
            ? Storage::disk(config('filesystems.default'))->url($this->profile->photo)
            : null;
    }

    public function cashierName(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->name ?? $this->email
        );
    }

    public function routeNotificationForFcm()
    {
        return $this->fcm_token;
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopeAdmin(Builder $builder)
    {
        return $builder->role('Admin');
    }

    public function scopeManager(Builder $builder)
    {
        return $builder->role('Manager');
    }

    public function scopeCashier(Builder $builder)
    {
        return $builder->role('Cashier');
    }
}