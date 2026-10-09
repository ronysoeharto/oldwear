<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\StoreProfile;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        Paginator::defaultView('partials.pagination');

        // Paksa HTTPS di production (setelah SSL aktif)
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        // Data toko & kategori tersedia di semua layout (navbar/footer) tanpa hard-code
        View::composer(['layouts.*', 'partials.*', 'pages.*', 'auth.*', 'admin.*'], function ($view) {
            static $shared;
            $shared ??= [
                'store' => StoreProfile::current(),
                'footerCategories' => Schema::hasTable('categories')
                    ? Category::orderBy('name')->take(7)->get(['name', 'slug'])
                    : collect(),
            ];
            $view->with($shared);
        });
    }
}
