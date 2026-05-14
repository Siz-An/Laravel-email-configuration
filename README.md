# Laravel Email Configuration

Database-backed email templates with a REST API and test-send flow. **This Composer package only ships installable files** (stubs). After you publish, **controllers, routes, models, requests, services, config, and migrations live under your application** (`app/`, `routes/`, `config/`, `database/migrations/`), not in `vendor/` as executable code.

The `vendor/` copy keeps a tiny auto-discovered provider so `php artisan vendor:publish` can copy (or refresh) those stubs.

## Requirements

- PHP 8.1+
- Laravel 10.48+, 11.x, or 12.x

## Installation

### 1. Require the package

```bash
composer require siz-an/laravel-email-configuration
```

### 2. Publish all application files

```bash
php artisan vendor:publish --tag=email-configuration
```

This copies:

| Destination | Contents |
|-------------|----------|
| `app/EmailConfiguration/` | `EmailConfigurationServiceProvider`, `Models\EmailConfiguration`, `Http\Controllers\…`, `Http\Requests\…`, `Services\EmailTemplateRenderer` |
| `routes/email-configuration.php` | API route definitions |
| `config/email-configuration.php` | Settings (prefix, middleware, table name, pagination, `user_model`) |
| `database/migrations/2026_05_14_000000_create_email_configurations_table.php` | Creates `email_configurations` |

### 3. Register the **application** service provider

Add **one** line (Laravel 11+):

`bootstrap/providers.php`

```php
<?php

return [
    App\Providers\AppServiceProvider::class,
    App\EmailConfiguration\EmailConfigurationServiceProvider::class,
];
```

Laravel 10: add the same class to the `providers` array in `config/app.php`.

### 4. Migrate

```bash
php artisan migrate
```

### Publish tags (granular)

| Tag | Copies |
|-----|--------|
| `email-configuration` | Everything (recommended) |
| `email-configuration-app` | Only `app/EmailConfiguration/**` |
| `email-configuration-routes` | Only `routes/email-configuration.php` |
| `email-configuration-config` | Only `config/email-configuration.php` |
| `email-configuration-migrations` | Only the migration file |

To overwrite existing published files with the latest stubs from the package:

```bash
php artisan vendor:publish --tag=email-configuration --force
```

### From GitHub (VCS)

Add the repository to your app’s `composer.json`, require `dev-main` (or your branch), then follow steps 2–4 above.

### Local path (development)

Use a `path` repository pointing at this package clone, `composer update`, then steps 2–4.

## Configuration

Edit `config/email-configuration.php` after publishing.

| Key | Purpose |
|-----|---------|
| `route_prefix` | URL prefix before `email-configurations` (default: `api`). |
| `middleware` | Route middleware (default: `['api']`). Add `auth:sanctum` etc. as needed. |
| `table` | Database table name. |
| `user_model` | FQCN for `createdBy` / `updatedBy`. If empty, uses the default auth provider model, then `App\Models\User`. |
| `per_page` / `per_page_max` | Index pagination defaults and cap. |

Environment variables use the `EMAIL_CONFIGURATION_` prefix (see the config file).

## Routes

Defined in **`routes/email-configuration.php`** (in your app). With the default prefix, list templates at `GET /api/email-configurations`.

| Method | URI | Action |
|--------|-----|--------|
| GET | `/{prefix}/email-configurations` | Paginated list |
| POST | `/{prefix}/email-configurations` | Create |
| GET | `/{prefix}/email-configurations/{id}` | Show |
| PUT/PATCH | `/{prefix}/email-configurations/{id}` | Update |
| DELETE | `/{prefix}/email-configurations/{id}` | Delete |
| POST | `/{prefix}/email-configurations/{id}/test-send` | Test send |

## Index: pagination and filters

`GET /api/email-configurations` returns a Laravel [paginator](https://laravel.com/docs/pagination) JSON payload.

| Query | Description |
|-------|-------------|
| `per_page` | Page size (capped by `per_page_max`). |
| `search` | Partial match on `name`, `subject`, `slug`. |
| `type` | Exact `type` column match. |
| `is_active` | Boolean filter. |

## Model and relations

`App\EmailConfiguration\Models\EmailConfiguration`:

- `createdBy()` / `updatedBy()` — `BelongsTo` your user model.
- `created_by_display` / `updated_by_display` — optional display strings when relations are loaded.

## Programmatic usage

```php
use App\EmailConfiguration\Models\EmailConfiguration;

$template = EmailConfiguration::query()
    ->where('slug', 'welcome-email')
    ->first();

$template?->load('createdBy', 'updatedBy');
$label = $template?->created_by_display;
```

Placeholders in stored HTML/text: `{{variable_name}}`. The published `App\EmailConfiguration\Services\EmailTemplateRenderer` can render them before sending mail.

## Upgrading

### From 2.x

Re-publish with `--force` to refresh stubs, then review diffs (you may have customized published files). Ensure `App\EmailConfiguration\EmailConfigurationServiceProvider` is registered; remove any reliance on code under `vendor/…/src` for this feature (it is no longer shipped there).

### From 1.x

If you originally migrated from the old vendor-loaded migration, publishing uses the same migration basename; `php artisan migrate` should not duplicate the table. Still register the app provider and publish routes/app files for v3.

## License

MIT.

## Publishing on Packagist

1. Push this repository to GitHub (`main` is typical).
2. Tag releases (for example `v3.0.0`).
3. Submit `https://github.com/Siz-An/Laravel-email-configuration.git` on [packagist.org/packages/submit](https://packagist.org/packages/submit).
