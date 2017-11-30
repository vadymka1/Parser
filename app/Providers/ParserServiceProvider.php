<?php

namespace App\Providers;

use App\Repositories\NewsRepository;
use Illuminate\Support\ServiceProvider;

class ParserServiceProvider extends ServiceProvider
{
    protected $defer = true;
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Repositories\NewsRepository', function (){
            return new NewsRepository();
        });
    }
    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['App\Repositories\NewsRepository'];
    }
}
