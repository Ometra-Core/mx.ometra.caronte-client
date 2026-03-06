# Publishing Caronte Client Resources

This document describes how to publish Caronte Client package assets and configurations to your Laravel application.

## Overview

The Caronte Client package provides several publishable asset groups that you can copy to your application for customization:

1. **Configuration** - Package settings
2. **Views** - Blade view templates
3. **Migrations** - Database migrations
4. **Assets** - CSS and JavaScript files
5. **Inertia Components** - React/Vue components (when using Inertia.js)

## Publishing All Resources

To publish all assets and configurations at once:

```bash
php artisan vendor:publish --provider="Ometra\Caronte\Providers\CaronteServiceProvider"
```

Or use the convenience tag:

```bash
php artisan vendor:publish --tag=caronte
```

## Publishing Specific Resources

### Configuration

Publish only the Caronte configuration file:

```bash
php artisan vendor:publish --tag=caronte:config
```

**Published to**: `config/caronte.php`

**What it contains**:
- Server URL and API credentials
- JWT configuration
- Route prefix configuration
- Authentication features (2FA, HTTP requests)
- User management settings
- View rendering options (Inertia vs Blade)
- Database table prefix

**After publishing**: Copy the environment variables from `.env.example` to `.env`:

```env
CARONTE_URL=https://caronte-server.example.com
CARONTE_APP_ID=your-app-id
CARONTE_APP_SECRET=your-app-secret
CARONTE_ENFORCER_ISSUER=true
CARONTE_LOGIN_URL=/login
CARONTE_2FA=false
```

### Views

Publish the authentication and management view templates:

```bash
php artisan vendor:publish --tag=caronte:views
```

**Published to**: `resources/views/vendor/caronte/`

**Includes**:
- `auth/` - Login, 2FA, password recovery views
- `management/` - User and role management dashboards
- `layouts/` - Base and login layout templates
- `partials/` - Reusable view sections

**Usage**: After publishing, customize the views to match your application's design and branding.

### Migrations

Publish database migrations for Caronte tables:

```bash
php artisan vendor:publish --tag=caronte:migrations
```

**Published to**: `database/migrations/`

**Includes**:
- `*_create_caronte_users_table.php` - User authentication data
- `*_create_caronte_user_metadata_table.php` - User metadata storage

**After publishing**: Run migrations with:

```bash
php artisan migrate
```

### Assets

Publish CSS and JavaScript static files:

```bash
php artisan vendor:publish --tag=caronte-assets
```

**Published to**: `public/vendor/caronte/`

**Includes**:
- `css/` - Stylesheet files
- `js/` - JavaScript utilities

**Usage**: Reference these files in your layouts:

```blade
<link rel="stylesheet" href="{{ asset('vendor/caronte/css/caronte.css') }}">
<script src="{{ asset('vendor/caronte/js/main.js') }}"></script>
```

### Inertia Components

Publish React/Vue components (when using Inertia.js):

```bash
php artisan vendor:publish --tag=caronte:inertia
```

**Published to**: `resources/js/vendor/caronte/`

**Includes**:
- Authentication form components
- Management dashboard components
- Shared layout components

**Requires**: `USE_INERTIA=true` in `config/caronte.php`

## Verification

To verify that all publish sources are correctly configured, the package includes validation tests:

```bash
composer test tests/Feature/PublishCommandsTest.php
```

This validates:
- ✓ All source files exist
- ✓ Configuration keys are present
- ✓ Migrations are readable
- ✓ Views directory structure is correct
- ✓ Assets contain CSS files
- ✓ Inertia components exist

## Force Re-publishing

To overwrite previously published files:

```bash
php artisan vendor:publish --tag=caronte --force
```

**Caution**: This will overwrite customizations you've made to published files.

## Troubleshooting

### "Published assets not found"

1. Verify the package is installed: `composer show ometra/caronte-client`
2. Clear the cache: `php artisan config:clear && php artisan cache:clear`
3. Attempt the publish command again

### "File permissions denied"

Ensure your application directory has write permissions:

```bash
chown -R www-data:www-data storage/ public/ resources/
chmod -R 755 storage/ public/
```

### "Configuration is incomplete"

After publishing config, ensure all required environment variables are set in `.env`:

```bash
CARONTE_URL=
CARONTE_APP_ID=
CARONTE_APP_SECRET=
```

Then reload config:

```bash
php artisan config:clear
```

## See Also

- [README.md](./README.md) - Package overview
- [config/caronte.php](./config/caronte.php) - Configuration reference
- [CHANGELOG.md](./CHANGELOG.md) - Version history
