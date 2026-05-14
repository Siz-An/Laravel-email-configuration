<?php

use App\EmailConfiguration\Http\Controllers\EmailConfigurationController;
use Illuminate\Support\Facades\Route;

Route::prefix(config('email-configuration.route_prefix', 'api'))
    ->middleware(config('email-configuration.middleware', ['api']))
    ->group(function () {
        Route::get('email-configurations', [EmailConfigurationController::class, 'index']);
        Route::post('email-configurations', [EmailConfigurationController::class, 'store']);
        Route::get('email-configurations/{id}', [EmailConfigurationController::class, 'show']);
        Route::put('email-configurations/{id}', [EmailConfigurationController::class, 'update']);
        Route::patch('email-configurations/{id}', [EmailConfigurationController::class, 'update']);
        Route::delete('email-configurations/{id}', [EmailConfigurationController::class, 'destroy']);
        Route::post('email-configurations/{id}/test-send', [EmailConfigurationController::class, 'testSend']);
    });
