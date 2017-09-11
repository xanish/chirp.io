<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $colorSO = new \App\ServiceObjects\ColorServiceObject;
        view()->composer('layouts.app', function ($view) use ($colorSO) {
            $view->with('color', $colorSO->getColor());
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
