<?php

/**
 * Management dashboard and system control controller.
 *
 * PHP 8.1+
 *
 * @package   Ometra\Caronte\Http\Controllers
 * @author    Gabriel Ruelas <gruelas@gruelas.com>
 * @license   https://opensource.org/licenses/MIT MIT License
 */

namespace Ometra\Caronte\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Inertia\Response as InertiaResponse;
use Ometra\Caronte\Facades\Caronte;
use Ometra\Caronte\Api\RoleApiClient;
use Ometra\Caronte\CaronteRoleManager;
use Ometra\Caronte\CaronteRequest;

/**
 * Manages the main dashboard, token retrieval, metadata, and role synchronization.
 */
class ManagementController extends BaseController
{
    /**
     * Display the management dashboard with users and roles.
     *
     * @param  Request  $request  HTTP request object.
     * @return View|InertiaResponse  Management dashboard view with users and roles.
     */
    public function dashboard(Request $request): View | InertiaResponse
    {
        $users    = RoleApiClient::showUsers("", true);
        $users    = json_decode($users['data'], true);

        $rolesResponse = RoleApiClient::showRoles();
        $roles         = json_decode($rolesResponse['data'] ?? '[]', true);

        return $this->toView('management.index', [
            'callback_url' => $request->callback_url,
            'users'        => $users,
            'roles'        => $roles,
            'csrf_token'   => csrf_token(),
            'routes'       => [
                'usersList'           => route('caronte.management.users.list'),
                'usersCreate'         => route('caronte.management.users.store'),
                'usersUpdate'         => route('caronte.management.users.update'),
                'usersDelete'         => route('caronte.management.users.delete'),
                'userRolesList'       => route('caronte.management.users.roles.list', ['uri_user' => 'USER_ID']),
                'rolesList'           => route('caronte.management.roles.list'),
                'rolesCreate'         => route('caronte.management.roles.create'),
                'rolesUpdate'         => route('caronte.management.roles.update'),
                'rolesDelete'         => route('caronte.management.roles.delete'),
            ],
        ]);
    }

    /**
     * Retrieve the current user's authentication token.
     *
     * @return Response  Token response.
     */
    public function getToken(): Response
    {
        return Response(Caronte::getToken()->toString(), 200);
    }

    /**
     * Set or update user metadata.
     *
     * @param  Request              $request  HTTP request with metadata.
     * @return Response|RedirectResponse      Response or redirect.
     */
    public function setMetadata(Request $request): Response|RedirectResponse
    {
        return CaronteRequest::setMetadata(request: $request);
    }

    /**
     * Synchronize roles with Caronte server.
     *
     * @param  Request      $request  HTTP request object.
     * @return JsonResponse           JSON response indicating sync success.
     */
    public function synchronize(Request $request): JsonResponse
    {
        try {
            CaronteRoleManager::getRoles();
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }

        return response()->json(['success' => true]);
    }
}
