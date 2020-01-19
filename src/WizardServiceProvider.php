<?php

namespace Armincms\Wizard;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova; 

class WizardServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    { 
        $this->app->booted(function () {
            $this->routes();
        });

        Nova::serving(function (ServingNova $event) {
            Nova::script('wizard', __DIR__.'/../dist/js/tool.js');
        });
    }

    /**
     * Register the tool's routes.
     *
     * @return void
     */
    protected function routes()
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Route::middleware(['nova'])
                ->prefix('nova-vendor/wizard')
                ->namespace(__NAMESPACE__.'\\Http\\Controllers')
                ->group(function($router) { 
                    $router->post(
                        '/{resource}/{resourceId}', 'ValidateUpdateController@handle'
                    );

                    $router->post('/{resource}', 'ValidateStoreController@handle');
                }); 
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
