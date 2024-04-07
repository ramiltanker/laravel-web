<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

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
        // Директива для выделения активной ссылки
        Blade::directive('activeLink', function($route){
            // if (request()->is($route)){
            //     return "<?='active';
            return "<?php echo request()->is($route) ? 'active' : null;?>";
        });
    }
}
