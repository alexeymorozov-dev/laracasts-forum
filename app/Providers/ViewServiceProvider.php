<?php

namespace App\Providers;

use App\Models\Channel;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Share all channels for a dropdown and a select tag
        \View::composer(['layouts.app', 'threads.create'], function ($view) {
            $view->with('channels', Channel::all());
        });
    }
}
