<?php

namespace App\Filament\Tenant\Pages;

use Filament\Facades\Filament;
use Filament\Http\Responses\Auth\LoginResponse;
use Filament\Pages\Auth\Login;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class TenantLogin extends Login
{
    public function authenticate(): ?LoginResponse
    {
        $response = parent::authenticate();

        $user = Auth::user();

        if (! $user) {
            throw ValidationException::withMessages([
                'data.email' => 'Email atau password salah.',
            ]);
        }

        // ambil panel tenant dengan benar
        $panel = Filament::getPanel('tenant');

        // cek akses panel
        if (
            method_exists($user, 'canAccessPanel') &&
            ! $user->canAccessPanel($panel)
        ) {
            Auth::logout();

            throw ValidationException::withMessages([
                'data.email' => 'Kamu tidak punya akses ke aplikasi ini.',
            ]);
        }

        // update profile (aman kalau ada relasi)
        if (method_exists($user, 'profile')) {
            $user->profile()->updateOrCreate(
                [
                    'user_id' => $user->getKey(),
                ],
                [
                    'timezone' => 'Asia/Jakarta',
                ]
            );
        }

        return $response;
    }

    protected function getRedirectUrl(): string
    {
        $user = Auth::user();

        $panelUrl = Filament::getPanel('tenant')->getUrl();

        if (! $user) {
            return $panelUrl;
        }

        // redirect berdasarkan role
        if ($user->hasRole('Cashier')) {
            return '/pos';
        }

        if ($user->hasRole('Admin')) {
            return $panelUrl;
        }

        if ($user->hasRole('Owner')) {
            return $panelUrl;
        }

        return $panelUrl;
    }

    public function mount(): void
    {
        // kalau sudah login, langsung ke dashboard tenant
        if (Filament::auth()->check()) {
            redirect()->intended(
                Filament::getPanel('tenant')->getUrl()
            );
        }

        // demo auto fill
        if (app()->environment('demo')) {
            $this->form->fill([
                'email' => 'demo@lakasir.com',
                'password' => 'passwordsangatrahasia',
            ]);
        }
    }
}