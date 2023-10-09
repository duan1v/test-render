<?php

namespace App\Providers;

use App\Foundations\CustomUrlGenerator;
use App\Foundations\CustomVite;
use Illuminate\Foundation\Vite;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if (env('APP_ENV') != 'local') {
            $this->app->bind('url', function ($app) {
                return new CustomUrlGenerator(
                    $app['router']->getRoutes(),
                    $app->make('request')
                );
            });
            $this->app->bind(Vite::class, function ($app) {
                return new CustomVite();
            });
        }
    }

    public function boot(UrlGenerator $url)
    {
        if (env('APP_ENV')  != 'local') {
            $url->forceScheme('https');
        }
    }
}
