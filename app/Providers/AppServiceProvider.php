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
