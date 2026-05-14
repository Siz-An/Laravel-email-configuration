<?php

namespace Sizan\EmailConfiguration;

use Illuminate\Support\ServiceProvider;

class EmailConfigurationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/email-configuration.php', 'email-configuration');
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/email-configuration.php' => config_path('email-configuration.php'),
        ], 'email-configuration-config');

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
    }
}
