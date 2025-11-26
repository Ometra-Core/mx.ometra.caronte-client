# Caronte Client (Laravel package)

Caronte Client is a Laravel package that provides distributed authentication middlewares and a set of commands for secure self-management in PHP projects. It allows seamless integration of robust authentication, access control, and management of users and roles, all configurable and extensible within the framework.

---

## 🏁 Quickstart

### Installation

composer require caronte/client### Environment Configuration

Add the following environment variables to your `.env` file and adjust them according to your project needs:

| Variable                        | Example Value                      | Description                                                      |
|----------------------------------|------------------------------------|------------------------------------------------------------------|
| `CARONTE_URL`                   | `http://caronte.test/`             | FQDN of the Caronte server for authentication                    |
| `CARONTE_TOKEN_KEY`             | *(required if applicable)*         | Symmetric key for authentication                                 |
| `CARONTE_ALLOW_HTTP_REQUESTS`   | `false`                            | Allow HTTP requests (not recommended in production)              |
| `CARONTE_ISSUER_ID`             | `net.example`                      | Issuer ID                                                        |
| `CARONTE_ENFORCE_ISSUER`        | `true`                             | Enforce strict issuer validation                                 |
| `CARONTE_APP_ID`                | `net.example`                      | Registered application ID                                        |
| `CARONTE_APP_SECRET`            | `OgNy19Z...`                       | Registered application secret                                    |
| `CARONTE_2FA`                   | `false`                            | Enable two-factor authentication                                 |
| `CARONTE_ROUTES_PREFIX`         | *(optional)*                       | Prefix for protected routes                                      |
| `CARONTE_SUCCESS_URL`           | `/`                                | Redirect URL after authentication                                |
| `CARONTE_LOGIN_URL`             | `/login`                           | Login route                                                      |
| `CARONTE_UPDATE_USER`           | `false`                            | Update users in local DB (requires migration)                    |
| `CARONTE_TOKEN_TTL`             | `460`                              | Token time-to-live (in seconds)                                  |
| `APP_TIMEZONE`                  | `America/Mexico_City`              | Application timezone                                             |

#### Real-world configuration example

CARONTE_URL=http://caronte.test/
CARONTE_ALLOW_HTTP_REQUESTS=false
CARONTE_ISSUER_ID=net.example
CARONTE_ENFORCE_ISSUER=true
CARONTE_APP_ID=net.example
CARONTE_APP_SECRET="OgNy19ZMRLXBsuAwTQSbpbzUkpE626N1SUfaeygE"
CARONTE_2FA=false
CARONTE_ROUTES_PREFIX=""
CARONTE_SUCCESS_URL="/"
CARONTE_LOGIN_URL="/login"
CARONTE_UPDATE_USER=false
APP_TIMEZONE=America/Mexico_City
CARONTE_TOKEN_TTL=460### Migrations (optional)

If you want to enable user synchronization (`CARONTE_UPDATE_USER=true`), run the migrations:

php artisan migrate---

## 🛠 Available Commands

This package includes artisan commands (prefix `caronte-client`) for administration and management directly from the console. Example of common commands:

| Command                                   | Description                                                |
|--------------------------------------------|------------------------------------------------------------|
| `php artisan caronte-client:sync-roles`    | Synchronize roles between Caronte and your local app       |
| `php artisan caronte-client:list-roles`    | List available roles                                      |
| `php artisan caronte-client:create-role`   | Create a new role                                         |
| `php artisan caronte-client:update-role`   | Update role properties                                    |
| `php artisan caronte-client:delete-role`   | Delete a role                                             |
| `php artisan caronte-client:assign-role`   | Assign a role to a user                                   |
| `php artisan caronte-client:remove-role`   | Remove a role from a user                                 |
| `php artisan caronte-client:list-users`    | List registered users                                     |
| `php artisan caronte-client:create-user`   | Create a user (local or synchronized)                     |
| `php artisan caronte-client:update-user`   | Edit a user (local or synchronized)                       |
| `php artisan caronte-client:delete-user`   | Delete a user                                             |
| `php artisan caronte-client:show-user-roles`| Show all roles assigned to a user                        |
| `php artisan caronte-client:attach-roles`  | Attach several roles to users (batch)                     |
| `php artisan caronte-client:detach-roles`  | Detach roles from users (batch)                           |
| `php artisan caronte-client:status`        | Display connection and authentication registries info      |

> _Use_ `php artisan list | grep caronte-client` _to see all available commands and descriptions in your project._
