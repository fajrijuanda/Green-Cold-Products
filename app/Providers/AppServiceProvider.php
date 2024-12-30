<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\ResetPassword;
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
    public function boot()
    {
        // Overwrite the public_path() helper
        app()->bind('path.public', function () {
            return base_path('public_html'); // Sesuaikan dengan nama folder public Anda
        });

        // Kustomisasi email reset password
        ResetPassword::toMailUsing(function ($notifiable, $token) {
            // Membuat URL reset password yang dikirimkan melalui email
            $resetUrl = url('/reset-password') . '?token=' . $token . '&email=' . urlencode($notifiable->getEmailForPasswordReset());

            // Mengirimkan email menggunakan template custom
            return (new \Illuminate\Notifications\Messages\MailMessage)
                ->view('emails.auth.send-reset-link', [
                    'resetUrl' => $resetUrl, // URL yang digunakan di template
                    'email' => $notifiable->getEmailForPasswordReset() // Email penerima
                ])
                ->subject('Reset Your Password'); // Subjek email
        });
    }
}
