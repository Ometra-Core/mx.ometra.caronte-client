# Caronte Client (Laravel Package)

Caronte Client is a Laravel package that provides distributed JWT authentication with middleware, role-based access control, and comprehensive user/role management commands for Laravel applications. It connects your application to a centralized Caronte authentication server for secure, scalable multi-tenant authentication.

---

### Main Features

- **JWT-based authentication** with automatic token renewal
- **Role-based access control** (RBAC) with fine-grained permissions
- **Dual authentication model**: User tokens (JWT) + App tokens (API)
- **Laravel middleware** for session and role validation
- **Artisan commands** for autonomous user/role management
- **Inertia.js support** for modern SPA rendering
- **Configurable table prefix** for multi-tenant deployments
- **Zero local caching** - all data fetched fresh from server

---

## 🏁 Quickstart

## Installation

Install Caronte Client via Composer:

```bash
composer require ometra/caronte-client
```

### Publish Assets (Optional)

Publish configuration, views, and migrations as needed:

```bash
# Publish config file
php artisan vendor:publish --tag=caronte:config

# Publish views (for customization)
php artisan vendor:publish --tag=caronte:views

# Publish migrations (if UPDATE_LOCAL_USER=true)
php artisan vendor:publish --tag=caronte:migrations
php artisan migrate
```

---

## Configuration

The Caronte Client package is designed to minimize `.env` pollution. **Only authentication secrets** need to be defined in the host application's `.env`. All other settings have sensible defaults in the package's config file.

### Required Environment Variables (Secrets)

Add **only these** to your application's `.env`:

| Variable             | Example Value                 | Description                   |
| -------------------- | ----------------------------- | ----------------------------- |
| `CARONTE_URL`        | `https://caronte.example.com` | FQDN of Caronte server        |
| `CARONTE_APP_ID`     | `app.example.com`             | Registered application ID     |
| `CARONTE_APP_SECRET` | `OgNy19ZMRLXBsuAwTQSbpbzU...` | Registered application secret |

### Optional Environment Variables

These can be overridden if needed, but have defaults in `config/caronte.php`:

| Variable                 | Default Value | Description                            |
| ------------------------ | ------------- | -------------------------------------- |
| `CARONTE_ISSUER_ID`      | `''`          | JWT issuer ID (if ENFORCE_ISSUER=true) |
| `CARONTE_ENFORCE_ISSUER` | `true`        | Enforce strict issuer validation       |

### Non-Environment Configuration (Defaults)

These settings are configured in `config/caronte.php` with sensible defaults:

- `USE_2FA`: `false` - Enable two-factor authentication
- `ALLOW_HTTP_REQUESTS`: `false` - Disable SSL verification (dev only)
- `ROUTES_PREFIX`: `''` - Prefix for Caronte routes
- `SUCCESS_URL`: `'/'` - Post-login redirect
- `LOGIN_URL`: `'/login'` - Login route path
- `UPDATE_LOCAL_USER`: `false` - Sync users to local database
- `USE_INERTIA`: `false` - Enable Inertia.js rendering
- `table_prefix`: `'CC_'` - Database table prefix (for migrations)

To customize any of these, publish the config:

```bash
php artisan vendor:publish --tag=caronte:config
```

### Migrations (Optional)

If you enable local user synchronization (`UPDATE_LOCAL_USER=true`), publish and run migrations:

```bash
php artisan vendor:publish --tag=caronte:migrations
php artisan migrate
```

---

# 🛠 Available Commands

This package includes Artisan commands (prefix `caronte-client:`) for autonomous administration of users and roles.

### 🟢 Main Entry Point

```bash
php artisan caronte-client:management
```

Interactive wizard to manage **Users** and **Roles**. Operations are divided into two branches:

---

## 🛡 Role Management

Manage role definitions within your application scope.

| Command                                       | Description                 |
| --------------------------------------------- | --------------------------- |
| `php artisan caronte-client:create-role`      | Create a new role           |
| `php artisan caronte-client:update-role`      | Update role description     |
| `php artisan caronte-client:delete-role`      | Delete a role               |
| `php artisan caronte-client:show-roles`       | List all roles              |
| `php artisan caronte-client:management-roles` | Interactive role management |

---

## 👥 User Management

> **⚠️ Important Workflow**
>
> To manage a user's roles, the user **MUST** first be linked to the application:
>
> 1. User exists in system
> 2. Run `caronte-client:attach-roles` to link roles
> 3. Then use update/delete operations

| Command                                        | Description                          |
| ---------------------------------------------- | ------------------------------------ |
| `php artisan caronte-client:create-user`       | Create a new user                    |
| `php artisan caronte-client:update-user`       | Update user details                  |
| `php artisan caronte-client:delete-user-roles` | Remove roles from user               |
| `php artisan caronte-client:show-user-roles`   | Show user's assigned roles           |
| `php artisan caronte-client:attach-roles`      | Link roles to user (required first!) |
| `php artisan caronte-client:management-users`  | Interactive user management          |

---

## Usage Examples

### Authenticating Users

```php
use Caronte;
// Retrieve the current JWT token
$token = Caronte::getToken();
// Get the authenticated user object from the token
$user = Caronte::getUser();
```

### Middleware Integration

Add Caronte middleware to your routes for session and role validation:

```php
// In your routes/web.php or routes/api.php
Route::middleware(['Caronte.ValidateSession'])->group(function () {
    Route::get('/dashboard', function () {
        // Only accessible to authenticated users
    });
});

Route::middleware(['Caronte.ValidateRoles:administrator,manager'])->group(function () {
    Route::get('/admin', function () {
        // Only accessible to users with administrator or manager roles (or root)
    });
});
```

### Permission Checks in Code

```php
use Ometra\Caronte\Helpers\PermissionHelper;
// Check if the user has access to the application
if (PermissionHelper::hasApplication()) {
    // User has access
}

// Check if the user has a specific role
if (PermissionHelper::hasRoles(['administrator', 'editor'])) {
    // User has one of the required roles
}
```
