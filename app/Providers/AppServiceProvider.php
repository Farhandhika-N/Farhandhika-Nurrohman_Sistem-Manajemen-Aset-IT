<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Notifications\ResetPassword;
use App\Models\Asset;
use App\Models\User;
use App\Observers\AssetObserver;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Carbon::setLocale('id');

        Gate::define('admin', function (User $user) {
            return $user->role === 'admin';
        });

        Asset::observe(AssetObserver::class);

        // Email reset password memakai Bahasa Indonesia & tautan ke form reset
        ResetPassword::toMailUsing(function ($notifiable, $token) {
            return (new \Illuminate\Notifications\Messages\MailMessage)
                ->subject('Reset Password - Sistem Manajemen Aset')
                ->line('Kami menerima permintaan reset password untuk akun Anda.')
                ->line('Klik tombol di bawah untuk membuat password baru. Tautan berlaku selama 60 menit.')
                ->action('Reset Password', route('password.reset', [
                    'token' => $token,
                    'email' => $notifiable->getEmailForPasswordReset(),
                ]))
                ->line('Jika Anda tidak meminta reset password, abaikan email ini.');
        });
    }
}
