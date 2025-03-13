<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
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
        $this->loadMigrationsFrom([
            database_path('migrations'), // Default
            database_path('migrations/user'),
            database_path('migrations/other'),
            database_path('migrations/master-data'),
            database_path('migrations/transaction'),
            database_path('migrations/booking'),
        ]);

        Blade::directive('currency', function ($expression) {
            return "<?php echo App\Helpers\NumberFormatter::format($expression); ?>";
        });
    }
}
