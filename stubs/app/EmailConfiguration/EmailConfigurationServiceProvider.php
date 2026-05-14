<?php

namespace App\EmailConfiguration;

use Illuminate\Support\ServiceProvider;

class EmailConfigurationServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $routeFile = base_path('routes/email-configuration.php');

        if (is_file($routeFile)) {
            $this->loadRoutesFrom($routeFile);
        }
    }
}
