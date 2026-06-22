<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Kemitraan;
use App\Models\User;
use App\Models\Chat;
use Illuminate\Database\Eloquent\Builder;
use App\Observers\KemitraanObserver;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Force HTTPS pada production (Railway reverse proxy)
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        Kemitraan::observe(KemitraanObserver::class);

        View::composer('*', function ($view) {
            static $admin = null;

            if (is_null($admin)) {
                $admin = User::isAdmin(true)->first();
            }

            $unreadCount = 0;
            if (Auth::check()) {
                /** @var Builder<Chat> $q */
                $q = Chat::query()
                    ->where('id_penerima', Auth::id())
                    ->where('status', 'terkirim');
                $unreadCount = $q->count();
            }

            $view->with([
                'admin' => $admin,
                'unread_messages_count' => $unreadCount
            ]);
        });
    }
}
