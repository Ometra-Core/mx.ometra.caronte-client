<?php

/**
 * Role management controller for CRUD and user-role association operations.
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
 * Handles role operations and user-role association/disassociation.
 *
 * Provides endpoints for retrieving roles, assigning roles to users, and
 * removing roles from users.
 */
class RoleController extends BaseController
{
    /**
     * List all available roles.
     *
     * @param  Request     $request  HTTP request object.
     * @return JsonResponse           JSON list of roles.
     */
    public function index(Request $request): JsonResponse
    {
        $response = RoleApiClient::showRoles();

        return response()->json($response);
    }

    /**
     * Get all roles assigned to a user.
     *
     * @param  Request     $request    HTTP request object.
     * @param  string      $uri_user   User URI identifier.
     * @return JsonResponse             JSON list of user's roles.
     */
    public function listByUser(Request $request, string $uri_user): JsonResponse
    {
        $response = RoleApiClient::showUserRoles(uri_user: $uri_user);

        return response()->json($response);
    }

    /**
     * Attach a role to a user.
     *
     * @param  Request         $request  HTTP request with user and role URIs.
     * @return RedirectResponse          Redirect with success/error message.
     */
    public function attach(Request $request): RedirectResponse
    {
        $uri_user = $request->input('uri_user');
        $uri_rol  = $request->input('uri_rol');

        $response = RoleApiClient::assignRoleToUser(uriUser: $uri_user, uriApplicationRole: $uri_rol);

        if (!$response['success']) {
            return redirect()
                ->back()
                ->with('error', 'Error al enlazar el rol: ' . $response['error']);
        }

        return redirect()
            ->back()
            ->with('success', 'Rol enlazado correctamente.');
    }

    /**
     * Remove role(s) from a user.
     *
     * Supports removing a single role or all roles if `allRoles` flag is set.
     *
     * @param  Request         $request  HTTP request with user/role and optional allRoles flag.
     * @return RedirectResponse          Redirect with success/error message.
     */
    public function detach(Request $request): RedirectResponse
    {
        $uri_user = $request->input('uri_user');
        $uri_rol  = $request->input('uri_rol');

        if ($request->input('allRoles')) {
            $roles = RoleApiClient::showUserRoles(uri_user: $uri_user);
            $roles = json_decode($roles['data'], true);

            foreach ($roles as $role) {
                $response = RoleApiClient::deleteUserRole(
                    uri_user: $uri_user,
                    uri_applicationRole: $role['uri_applicationRole']
                );

                if (!$response['success']) {
                    return redirect()
                        ->back()
                        ->with('error', 'Error al eliminar el rol: ' . $response['error']);
                }
            }

            return redirect()
                ->back()
                ->with('success', 'Todos los roles eliminados correctamente.');
        } else {
            $response = RoleApiClient::deleteUserRole(
                uri_user: $uri_user,
                uri_applicationRole: $uri_rol
            );

            if (!$response['success']) {
                return redirect()
                    ->back()
                    ->with('error', 'Error al eliminar el rol: ' . $response['error']);
            }

            return redirect()
                ->back()
                ->with('success', 'Rol eliminado correctamente.');
        }
    }

    /**
     * Create a new role.
     *
     * @param  Request         $request  HTTP request with role name and description.
     * @return RedirectResponse          Redirect with success/error message.
     */
    public function create(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|regex:/^[a-zA-Z0-9_-]+$/',
            'description' => 'nullable|string|max:500',
        ]);

        $response = RoleApiClient::createRole(
            name: $request->input('name'),
            description: $request->input('description', ''),
        );

        if (!$response['success']) {
            return redirect()
                ->back()
                ->with('error', 'Error creating role: ' . $response['error']);
        }

        return redirect()
            ->back()
            ->with('success', 'Role created successfully.');
    }

    /**
     * Update a role's description.
     *
     * @param  Request         $request  HTTP request with role URI and new description.
     * @return RedirectResponse          Redirect with success/error message.
     */
    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'uri_role'    => 'required|string',
            'description' => 'nullable|string|max:500',
        ]);

        $response = RoleApiClient::updateRole(
            uriApplicationRole: $request->input('uri_role'),
            description: $request->input('description', ''),
        );

        if (!$response['success']) {
            return redirect()
                ->back()
                ->with('error', 'Error updating role: ' . $response['error']);
        }

        return redirect()
            ->back()
            ->with('success', 'Role updated successfully.');
    }

    /**
     * Delete a role.
     *
     * @param  Request         $request  HTTP request with role URI.
     * @return RedirectResponse          Redirect with success/error message.
     */
    public function delete(Request $request): RedirectResponse
    {
        $request->validate([
            'uri_role' => 'required|string',
        ]);

        $response = RoleApiClient::deleteRole(
            uriApplicationRole: $request->input('uri_role'),
        );

        if (!$response['success']) {
            return redirect()
                ->back()
                ->with('error', 'Error deleting role: ' . $response['error']);
        }

        return redirect()
            ->back()
            ->with('success', 'Role deleted successfully.');
    }
}
