<?php

/**
 * User management controller for CRUD and role assignment operations.
 *
 * PHP 8.1+
 *
 * @package   Ometra\Caronte\Http\Controllers
 * @author    Gabriel Ruelas <gruelas@gruelas.com>
 * @license   https://opensource.org/licenses/MIT MIT License
 */

namespace Ometra\Caronte\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Ometra\Caronte\Api\RoleApiClient;

/**
 * Handles user CRUD operations and user-role management.
 *
 * Provides endpoints for creating, updating, and deleting users, as well as
 * listing users and managing their role assignments.
 */
class UserController extends BaseController
{
    /**
     * Create a new user with initial role assignment.
     *
     * @param  Request         $request  HTTP request with user/role data.
     * @return RedirectResponse          Redirect with success/error message.
     */
    public function create(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'roles' => 'required|array|min:1',
            'roles.*' => 'string',
        ]);

        $name     = $request->input('name');
        $email    = $request->input('email');
        $roles    = $request->input('roles', []);
        $password = bin2hex(random_bytes(4));

        $response = RoleApiClient::createUser(
            name: $name,
            email: $email,
            password: $password,
            password_confirmation: $password,
        );

        if (!$response['success']) {
            return redirect()
                ->back()
                ->with('error', 'Error al crear al usuario: ' . $response['error']);
        }

        $responseData = json_decode($response['data'], true);
        $user         = $responseData['user'] ?? null;

        if (!$user || !isset($user['uri_user'])) {
            return redirect()
                ->back()
                ->with('error', 'Error al crear al usuario: respuesta invalida.');
        }

        foreach ($roles as $roleUri) {
            $assignResponse = RoleApiClient::assignRoleToUser(
                uriUser: $user['uri_user'],
                uriApplicationRole: $roleUri,
            );

            if (!$assignResponse['success']) {
                return redirect()
                    ->back()
                    ->with('error', 'Error al enlazar el rol: ' . $assignResponse['error']);
            }
        }

        return redirect()
            ->back()
            ->with('success', 'Usuario creado correctamente.');
    }

    /**
     * Update an existing user's name and roles.
     *
     * @param  Request         $request  HTTP request with updated user data.
     * @return RedirectResponse          Redirect with success/error message.
     */
    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'uri_user' => 'required|string',
            'name' => 'required|string|max:255',
            'roles' => 'array',
            'roles.*' => 'string',
        ]);

        $uri_user = $request->input('uri_user');
        $name     = $request->input('name');
        $roles    = $request->input('roles', []);

        $response = RoleApiClient::updateUser(
            uri_user: $uri_user,
            name: $name,
        );

        if (!$response['success']) {
            return redirect()
                ->back()
                ->with('error', 'Error al actualizar el usuario: ' . $response['error']);
        }

        $currentRolesResponse = RoleApiClient::showUserRoles(uri_user: $uri_user);

        if (!$currentRolesResponse['success']) {
            return redirect()
                ->back()
                ->with('error', 'Error al obtener los roles del usuario: ' . $currentRolesResponse['error']);
        }

        $currentRoles = json_decode($currentRolesResponse['data'] ?? '[]', true);
        $currentRoleUris = array_filter(
            array_map(
                fn($role) => $role['uri_applicationRole'] ?? null,
                $currentRoles,
            )
        );

        $requestedRoles = array_values(array_unique(array_filter($roles)));

        $rolesToAttach = array_diff($requestedRoles, $currentRoleUris);
        $rolesToDetach = array_diff($currentRoleUris, $requestedRoles);

        foreach ($rolesToAttach as $roleUri) {
            $assignResponse = RoleApiClient::assignRoleToUser(
                uriUser: $uri_user,
                uriApplicationRole: $roleUri,
            );

            if (!$assignResponse['success']) {
                return redirect()
                    ->back()
                    ->with('error', 'Error al enlazar el rol: ' . $assignResponse['error']);
            }
        }

        foreach ($rolesToDetach as $roleUri) {
            $detachResponse = RoleApiClient::deleteUserRole(
                uri_user: $uri_user,
                uri_applicationRole: $roleUri,
            );

            if (!$detachResponse['success']) {
                return redirect()
                    ->back()
                    ->with('error', 'Error al eliminar el rol: ' . $detachResponse['error']);
            }
        }

        return redirect()
            ->back()
            ->with('success', 'Usuario actualizado correctamente.');
    }

    /**
     * Delete a user account.
     *
     * @param  Request         $request  HTTP request with user to delete.
     * @return RedirectResponse          Redirect with success/error message.
     */
    public function delete(Request $request): RedirectResponse
    {
        $uri_user = $request->input('uri_user');
        $response = RoleApiClient::deleteUser(uri_user: $uri_user);

        if (!$response['success']) {
            return redirect()
                ->back()
                ->with('error', 'Error al eliminar el usuario: ' . $response['error']);
        }

        return redirect()
            ->back()
            ->with('success', 'Usuario eliminado correctamente.');
    }

    /**
     * List users with optional search filter.
     *
     * @param  Request     $request  HTTP request with optional search parameter.
     * @return JsonResponse           JSON list of users.
     */
    public function index(Request $request): JsonResponse
    {
        $usersApp = $request->input('usersApp') == 'false' ? false : true;
        $response = RoleApiClient::showUsers(
            paramSearch: $request->input('search') ?? '',
            usersApp: $usersApp
        );

        return response()->json($response);
    }
}
