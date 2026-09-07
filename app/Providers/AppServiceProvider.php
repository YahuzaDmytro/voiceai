<?php

namespace App\Providers;

use App\Telegram\BotClient;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(BotClient::class, fn () => new BotClient(
            token: (string) config('telegram.token'),
            baseUrl: (string) config('telegram.base_url'),
        ));
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
