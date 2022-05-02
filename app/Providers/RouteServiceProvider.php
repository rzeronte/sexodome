<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        Route::pattern('host', '[a-z0-9.]+');

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        //$this->mapApiRoutes();


        //$this->mapSexodomeRoutes();
        $this->mapTubeRoutes();
        //$this->mapBackendRoutes();
        //$this->mapApiV1Routes();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/api.php'));
    }

    private function mapTubeRoutes()
    {
        Route::namespace($this->namespace)
            ->group(base_path('routes/v1/tube.php'));
    }

    private function mapSexodomeRoutes()
    {
        Route::namespace($this->namespace)
            ->group(base_path('routes/v1/web.php'));
    }

    private function mapBackendRoutes()
    {
        Route::namespace($this->namespace)
            //->prefix('v1')
            ->group(base_path('routes/v1/backend.php'));
    }

    private function mapApiV1Routes()
    {
        Route::namespace($this->namespace)
            //->prefix('v1')
            ->group(base_path('routes/v1/api.php'));
    }
}
