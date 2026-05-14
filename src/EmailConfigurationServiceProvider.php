<?php

namespace Sizan\EmailConfiguration;

use Illuminate\Support\ServiceProvider;

/**
 * Installs publishable scaffolding only. Application code runs from your app/
 * after you publish and register App\EmailConfiguration\EmailConfigurationServiceProvider.
 */
class EmailConfigurationServiceProvider extends ServiceProvider
{
    protected string $stubs = __DIR__.'/../stubs';

    public function boot(): void
    {
        $s = $this->stubs;

        $config = ["{$s}/config/email-configuration.php" => config_path('email-configuration.php')];
        $migration = ["{$s}/database/migrations/2026_05_14_000000_create_email_configurations_table.php" => database_path('migrations/2026_05_14_000000_create_email_configurations_table.php')];
        $routes = ["{$s}/routes/email-configuration.php" => base_path('routes/email-configuration.php')];
        $app = [
            "{$s}/app/EmailConfiguration/EmailConfigurationServiceProvider.php" => app_path('EmailConfiguration/EmailConfigurationServiceProvider.php'),
            "{$s}/app/EmailConfiguration/Models/EmailConfiguration.php" => app_path('EmailConfiguration/Models/EmailConfiguration.php'),
            "{$s}/app/EmailConfiguration/Services/EmailTemplateRenderer.php" => app_path('EmailConfiguration/Services/EmailTemplateRenderer.php'),
            "{$s}/app/EmailConfiguration/Http/Controllers/EmailConfigurationController.php" => app_path('EmailConfiguration/Http/Controllers/EmailConfigurationController.php'),
            "{$s}/app/EmailConfiguration/Http/Requests/IndexEmailConfigurationRequest.php" => app_path('EmailConfiguration/Http/Requests/IndexEmailConfigurationRequest.php'),
            "{$s}/app/EmailConfiguration/Http/Requests/StoreEmailConfigurationRequest.php" => app_path('EmailConfiguration/Http/Requests/StoreEmailConfigurationRequest.php'),
            "{$s}/app/EmailConfiguration/Http/Requests/UpdateEmailConfigurationRequest.php" => app_path('EmailConfiguration/Http/Requests/UpdateEmailConfigurationRequest.php'),
            "{$s}/app/EmailConfiguration/Http/Requests/TestSendEmailRequest.php" => app_path('EmailConfiguration/Http/Requests/TestSendEmailRequest.php'),
        ];

        $this->publishes($config, 'email-configuration-config');
        $this->publishes($migration, 'email-configuration-migrations');
        $this->publishes($routes, 'email-configuration-routes');
        $this->publishes($app, 'email-configuration-app');
        $this->publishes(array_merge($config, $migration, $routes, $app), 'email-configuration');
    }
}
