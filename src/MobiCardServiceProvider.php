<?php

namespace SanTran\MobiCard;

use Illuminate\Support\ServiceProvider;

class MobiCardServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Publish config files
        $this->publishes([
            __DIR__ . '/config/mobicard.php' => config_path('mobicard.php'),
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('mobicard', function () {
            return new MobiCard();
        });
        $this->app->alias('mobicard', 'SanTran\MobiCard\MobiCard');
    }

}
