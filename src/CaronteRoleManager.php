<?php

/**
 * Manages application roles via Caronte server API.
 *
 * Provides direct API methods for role CRUD operations. All methods execute
 * immediately on Caronte server without local caching.
 *
 * PHP 8.1+
 *
 * @package   Ometra\Caronte
 * @author    Gabriel Ruelas <gruelas@gruelas.com>
 * @license   https://opensource.org/licenses/MIT MIT License
 */

namespace Ometra\Caronte;

use Equidna\Toolkit\Exceptions\UnauthorizedException;
use Ometra\Caronte\Api\RoleApiClient;

class CaronteRoleManager
{
    /**
     * Returns the app-bound authentication token.
     *
     * @return non-empty-string
     * @throws UnauthorizedException When token cannot be generated.
     */
    public static function getToken(): string
    {
        $token = base64_encode(sha1(config('caronte.APP_ID')) . ':' . config('caronte.APP_SECRET'));
        if (is_null($token) || empty($token)) {
            throw new UnauthorizedException('Token not found');
        }

        return $token;
    }

    /**
     * Returns the SHA1 hash of the application ID.
     *
     * @return string
     */
    public static function getAppId(): string
    {
        return sha1(config('caronte.APP_ID'));
    }

    /**
     * Returns all roles from the Caronte server.
     *
     * @return array<string, array>
     */
    public static function getRoles(): array
    {
        $response = RoleApiClient::showRoles();
        if (!$response['success']) {
            return [];
        }

        $roles = json_decode($response['data'], true) ?? [];
        $mappedRoles = [];
        foreach ($roles as $role) {
            $mappedRoles[$role['uri_applicationRole']] = $role;
        }

        return $mappedRoles;
    }

    /**
     * Creates a new role on the Caronte server.
     *
     * @param  non-empty-string $name        Role name.
     * @param  non-empty-string $description Role description.
     * @return array{success: bool, data: string|null, error: string|null}
     */
    public static function createRole(string $name, string $description): array
    {
        return RoleApiClient::createRole($name, $description);
    }

    /**
     * Updates an existing role on the Caronte server.
     *
     * @param  string $uriApplicationRole Application role URI identifier.
     * @param  string $description        Updated role description.
     * @return array{success: bool, data: string|null, error: string|null}
     */
    public static function updateRole(string $uriApplicationRole, string $description): array
    {
        return RoleApiClient::updateRole($uriApplicationRole, $description);
    }

    /**
     * Deletes a role from the Caronte server.
     *
     * @param  string $uriApplicationRole Application role URI identifier.
     * @return array{success: bool, data: string|null, error: string|null}
     */
    public static function deleteRole(string $uriApplicationRole): array
    {
        return RoleApiClient::deleteRole($uriApplicationRole);
    }
}
