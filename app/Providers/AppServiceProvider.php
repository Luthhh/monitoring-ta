<?php

namespace App\Providers;

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
        \Dedoc\Scramble\Scramble::routes(function (\Illuminate\Routing\Route $route) {
            // Sembunyikan rute statis (Route::view) agar tidak mengotori dokumentasi API
            if (is_string($route->getActionName()) && str_contains($route->getActionName(), 'ViewController')) {
                return false;
            }

            return str_starts_with($route->uri, 'admin/') || 
                   str_starts_with($route->uri, 'dosen/') || 
                   str_starts_with($route->uri, 'mahasiswa/');
        });

        \Illuminate\Pagination\Paginator::useBootstrapFive();

        \Illuminate\Support\Facades\View::composer('*', function ($view) {
            if (\Illuminate\Support\Facades\Schema::hasTable('mahasiswas')) {
                $tahuns = \App\Models\Mahasiswa::select('tahun_masuk')
                    ->distinct()
                    ->orderBy('tahun_masuk', 'desc')
                    ->pluck('tahun_masuk');
                $view->with('all_years', $tahuns);
            }
        });
    }
}
