<?php

namespace App\Providers;
use Illuminate\Support\Collection;
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
        // Untuk sorting
        \Illuminate\Support\Facades\Blade::directive('sortablelink', function ($expression) {
            return "<?php echo \App\Helpers\SortableLink::render($expression); ?>";
        });

        Paginator::useBootstrapFive();
    }
}
