<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Xendit\Configuration;

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
        Configuration::setXenditKey(env('XENDIT_SECRET_KEY'));

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

        Blade::directive('dateFull', function ($expression) {
            return "<?php echo $expression ? Carbon\Carbon::parse($expression)->translatedFormat('d F Y, H:i:s') : $expression; ?>";
        });
        Blade::directive('date', function ($expression) {
            return "<?php echo $expression ? Carbon\Carbon::parse($expression)->translatedFormat('d F Y') : $expression; ?>";
        });
    }
}
