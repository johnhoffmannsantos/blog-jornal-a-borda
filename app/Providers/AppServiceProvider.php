<?php

namespace App\Providers;

use App\Models\Post;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache;
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
        // Definir locale para português brasileiro
        app()->setLocale('pt_BR');

        // Views em resources/views/pagination/ (textos em pt-BR; vendor/ pode estar sem permissão de escrita)
        Paginator::defaultView('pagination.bootstrap-5');
        Paginator::defaultSimpleView('pagination.simple-bootstrap-5');

        View::composer('admin.settings.index', function ($view) {
            $cronLastPublish = Cache::get('scheduler_posts_publish_last_at');
            $scheduledPendingCount = 0;
            try {
                $scheduledPendingCount = Post::query()->where('status', 'scheduled')->count();
            } catch (\Throwable) {
                //
            }
            $view->with(compact('cronLastPublish', 'scheduledPendingCount'));
        });
    }
}
