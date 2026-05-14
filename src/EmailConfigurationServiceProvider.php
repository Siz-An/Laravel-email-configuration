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
        $migration = __DIR__.'/../database/migrations/2026_05_14_000000_create_email_configurations_table.php';
        $migrationTarget = database_path('migrations/2026_05_14_000000_create_email_configurations_table.php');

        $this->publishes([
            __DIR__.'/../config/email-configuration.php' => config_path('email-configuration.php'),
        ], 'email-configuration-config');

        $this->publishes([
            $migration => $migrationTarget,
        ], 'email-configuration-migrations');

        $this->publishes([
            __DIR__.'/../config/email-configuration.php' => config_path('email-configuration.php'),
            $migration => $migrationTarget,
        ], 'email-configuration');

        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
    }
}
