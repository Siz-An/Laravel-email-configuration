<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Route prefix
    |--------------------------------------------------------------------------
    |
    | URL segment before "email-configurations". Use "api" to match
    | GET /api/email-configurations when your app serves routes from the web root.
    |
    */
    'route_prefix' => env('EMAIL_CONFIGURATION_ROUTE_PREFIX', 'api'),

    /*
    |--------------------------------------------------------------------------
    | Middleware
    |--------------------------------------------------------------------------
    |
    | Middleware applied to package routes. Add authentication here, for example:
    | ['api', 'auth:sanctum']
    |
    */
    'middleware' => ['api'],

    /*
    |--------------------------------------------------------------------------
    | Table name
    |--------------------------------------------------------------------------
    */
    'table' => 'email_configurations',

    /*
    |--------------------------------------------------------------------------
    | User model (created_by / updated_by)
    |--------------------------------------------------------------------------
    |
    | Fully qualified class name for the user model used by createdBy() and
    | updatedBy() relationships. When null, the model from your default auth
    | provider (config/auth.php) is used, falling back to App\Models\User.
    |
    */
    'user_model' => env('EMAIL_CONFIGURATION_USER_MODEL'),

    /*
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
    */
    'per_page' => (int) env('EMAIL_CONFIGURATION_PER_PAGE', 15),

    'per_page_max' => (int) env('EMAIL_CONFIGURATION_PER_PAGE_MAX', 100),

];
