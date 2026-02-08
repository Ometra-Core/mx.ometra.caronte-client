<?php

/**
 * HTTP client for Caronte role and user management API.
 *
 * Provides methods for managing roles and users via the Caronte API using
 * app-level authentication tokens. All methods return standardized response arrays.
 *
 * PHP 8.1+
 *
 * @package   Ometra\Caronte\Api
 * @author    Gabriel Ruelas <gruelas@gruelas.com>
 * @license   https://opensource.org/licenses/MIT MIT License
 * @link      https://github.com/Ometra-Core/mx.ometra.caronte-client Documentation
 */

namespace Ometra\Caronte\Api;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Exception;
use Ometra\Caronte\CaronteRoleManager;

/**
 * Handles HTTP requests to the Caronte authentication server.
 *
 * @author Gabriel Ruelas
 * @license MIT
 * @version 1.4.0
 */
class RoleApiClient
{
    private function __construct()
    {
        // Static-only class
    }

    /**
     * Returns all roles for the current application.
     *
     * @return array{success: bool, data: string|null, error: string|null}
     */
    public static function showRoles(): array
    {
        return self::makeRequest(
            method: 'get',
            endpoint: 'api/app/applications/' . CaronteRoleManager::getAppId() . '/roles'
        );
    }

    /**
     * Returns users matching the search criteria.
     *
     * @param  string $paramSearch  Search term for filtering users.
     * @param  bool   $usersApp     Filter by application users only.
     * @return array{success: bool, data: string|null, error: string|null}
     */
    public static function showUsers(string $paramSearch, bool $usersApp = false): array
    {
        return self::makeRequest(
            method: 'get',
            endpoint: 'api/app/users/',
            data: [
                'search' => $paramSearch,
                'app_users' => $usersApp ? 'true' : 'false',
            ]
        );
    }

    /**
     * Assigns a role to a user.
     *
     * @param  string $uriUser            User URI identifier.
     * @param  string $uriApplicationRole Application role URI identifier.
     * @return array{success: bool, data: string|null, error: string|null}
     */
    public static function assignRoleToUser(string $uriUser, string $uriApplicationRole): array
    {
        return self::makeRequest(
            method: 'post',
            endpoint: 'api/app/users/roles/' . $uriApplicationRole . '/' . $uriUser,
            data: [
                'uri_user' => $uriUser,
                'uri_applicationRole' => $uriApplicationRole,
            ]
        );
    }

    /**
     * Creates a new role for the current application.
     *
     * @param  string $name        Role name.
     * @param  string $description Role description.
     * @return array{success: bool, data: string|null, error: string|null}
     */
    public static function createRole(string $name, string $description): array
    {
        return self::makeRequest(
            method: 'post',
            endpoint: 'api/app/applications/' . CaronteRoleManager::getAppId() . '/roles',
            data: [
                'description' => $description,
                'name' => $name,
            ]
        );
    }

    /**
     * Updates an existing role's description.
     *
     * @param  string $uriApplicationRole Application role URI identifier.
     * @param  string $description        Updated role description.
     * @return array{success: bool, data: string|null, error: string|null}
     */
    public static function updateRole(string $uriApplicationRole, string $description): array
    {
        return self::makeRequest(
            method: 'put',
            endpoint: 'api/app/applications/' . CaronteRoleManager::getAppId() . '/roles/' . $uriApplicationRole,
            data: ['description' => $description]
        );
    }

    /**
     * Deletes a role from the current application.
     *
     * @param  string $uriApplicationRole Application role URI identifier.
     * @return array{success: bool, data: string|null, error: string|null}
     */
    public static function deleteRole(string $uriApplicationRole): array
    {
        return self::makeRequest(
            method: 'delete',
            endpoint: 'api/app/applications/' . CaronteRoleManager::getAppId() . '/roles/' . $uriApplicationRole
        );
    }

    /**
     * Creates a new user in the Caronte system.
     *
     * @param  string $name                  User's full name.
     * @param  string $email                 User's email address.
     * @param  string $password              User's password.
     * @param  string $password_confirmation Password confirmation.
     * @return array{success: bool, data: string|null, error: string|null}
     */
    public static function createUser(string $name, string $email, string $password, string $password_confirmation): array
    {
        return self::makeRequest(
            method: 'post',
            endpoint: 'api/app/users',
            data: [
                'name' => $name,
                'email' => $email,
                'password' => $password,
                'password_confirmation' => $password_confirmation,
            ]
        );
    }

    /**
     * Updates a user's name.
     *
     * @param  string $uri_user User URI identifier.
     * @param  string $name     Updated user name.
     * @return array{success: bool, data: string|null, error: string|null}
     */
    public static function updateUser(string $uri_user, string $name): array
    {
        return self::makeRequest(
            method: 'put',
            endpoint: 'api/app/users/' . $uri_user,
            data: ['name' => $name]
        );
    }

    /**
     * Removes a role from a user.
     *
     * @param  string $uri_user           User URI identifier.
     * @param  string $uri_applicationRole Application role URI identifier.
     * @return array{success: bool, data: string|null, error: string|null}
     */
    public static function deleteUserRole(string $uri_user, string $uri_applicationRole): array
    {
        return self::makeRequest(
            method: 'delete',
            endpoint: 'api/app/users/roles/' . $uri_applicationRole . '/' . $uri_user
        );
    }

    /**
     * Returns all roles assigned to a user.
     *
     * @param  string $uri_user User URI identifier.
     * @return array{success: bool, data: string|null, error: string|null}
     */
    public static function showUserRoles(string $uri_user): array
    {
        return self::makeRequest(
            method: 'get',
            endpoint: 'api/app/users/' . $uri_user . '/roles'
        );
    }

    /**
     * Deletes a user from the Caronte system.
     *
     * @param  string $uri_user User URI identifier.
     * @return array{success: bool, data: string|null, error: string|null}
     */
    public static function deleteUser(string $uri_user): array
    {
        return self::makeRequest(
            method: 'delete',
            endpoint: 'api/app/users/' . $uri_user
        );
    }

    /**
     * Makes an authenticated HTTP request to the Caronte server.
     *
     * @param  string $method   HTTP method (get, post, put, delete).
     * @param  string $endpoint API endpoint path (without base URL).
     * @param  array  $data     Request payload data.
     * @return array{success: bool, data: string|null, error: string|null}
     */
    private static function makeRequest(string $method, string $endpoint, array $data = []): array
    {
        try {
            $baseUrl = rtrim(config('caronte.URL'), '/');
            $url = $baseUrl . '/' . ltrim($endpoint, '/');

            $verifySSL = config('caronte.ALLOW_HTTP_REQUESTS') ? false : true;

            $response = Http::withHeaders([
                'Authorization' => 'Token ' . CaronteRoleManager::getToken(),
                'Accept' => 'application/json',
            ])->withOptions(['verify' => $verifySSL])->{$method}($url, $data);

            if ($response->failed()) {
                throw new RequestException($response);
            }

            return ['success' => true, 'data' => $response->body(), 'error' => null];
        } catch (RequestException | Exception $e) {
            return ['success' => false, 'data' => null, 'error' => $e->getMessage()];
        }
    }
}
