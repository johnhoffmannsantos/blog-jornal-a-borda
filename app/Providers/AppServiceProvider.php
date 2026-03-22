<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
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
        // Definir locale para português brasileiro
        app()->setLocale('pt_BR');

        // Views em resources/views/pagination/ (textos em pt-BR; vendor/ pode estar sem permissão de escrita)
        Paginator::defaultView('pagination.bootstrap-5');
        Paginator::defaultSimpleView('pagination.simple-bootstrap-5');
    }
}
