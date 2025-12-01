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
| `CARONTE_TOKEN_TTL`             | `460`                              | Token time-to-live (in seconds)                                                

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

# 🛠 Available Commands

This package includes artisan commands (prefix `caronte-client`) for administration and management directly from the console for the autonomous administration of each system

##### **🟢 Main Entry Point**

`php artisan caronte-client:management`
Interactive menu to manage **Users** and **Roles** via wizard. 
From here, operations are divided into two main branches: **Role Management** and **User Management**. 

##### 🛡 1. Role Management

Manage the definitions of roles within the application scope.


| Name  | Command  | Description  |   
|---|---|---|
| *create role*  | `php artisan caronte-client:create-role`  | Create a new role  |
| **manage an existing role**  | `php artisan caronte-client:management-roles`  | Management of existing roles  |
| *view existing roles*  | `php artisan caronte-client:show-roles`  | List existing roles  |

**Commands for the CRUD of roles**

| Name  | Command  | Description  |   
|---|---|---|
| *edit role*  | `php artisan caronte-client:create-role {uri_rol}`  | Update the  description of a role  |
| *delete a role*  | `php artisan caronte-client:delete-role {uri_rol}`  | Delete a role  |


#### 👥 2. User Management & Workflow

User management allows for full CRUD operations, but strict rules apply to ensure data integrity within the application context.

> [!IMPORTANT] **⚠️ Dependency Warning: Link Roles First**
> 
> To perform specific operations on a user (like updating details or managing their roles), the user **MUST** be linked to the application first.
> 
> **The Flow:**
> 
> 1.  User exists in the system.
>     
> 2.  **Execute `caronte-client:attached-roles`** to link an App Role to the user.
>     
> 3.  Now you can use `update-user`, `delete-roles-user`, etc.

| Name  | Command  | Description  |   
|---|---|---|
| *create user*  | `php artisan caronte-client:create-user`  | Create a user  |
| **manage an existing user**  | `php artisan caronte-client:management-users`  | Managing an existing user  |
| **attached roles to a user**  | `php artisan caronte-client:attached-roles`  | Attached roles an existing user  |

**Commands for the management users**
First of all, the user is searched for and selected, then it is managed. 
`Note: It is very important to take into account the aforementioned workflow`

| Name  | Command  | Description  |   
|---|---|---|
| *edit user*  | `php artisan caronte-client:update-user {uri_user} {name_user}`  | Update the  name of a user  |
| **delete roles associated with the user**  | `php artisan caronte-client:delete-roles-user {uri_user} {name_user}`  | Removes app roles that belong to the user  |
| *show roles in the application*  | `php artisan caronte-client:users-roles {uri_user}`  | Show Roles attached by user within the application  |

**Options to revoke roles**
__There are two options: select a specific role to remove or remove all roles associated with the user in the application__


