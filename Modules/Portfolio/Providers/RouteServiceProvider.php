<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->mapWebRoutes();
    }

    protected function mapWebRoutes(): void
    {
        Route::middleware('web')
        ->namespace('Modules\Portfolio\App\Http\Controllers')
        ->group(module_path('Portfolio', '/Routes/web.php')); // Or your actual route file
    }
}
