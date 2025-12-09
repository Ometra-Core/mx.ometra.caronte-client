<?php

/**
 * @author Gabriel Ruelas
 * @license MIT
 * @version 1.3.2
 */

namespace Ometra\Caronte\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Ometra\Caronte\CaronteRequest;
use Ometra\Caronte\Facades\Caronte;
use Ometra\Caronte\Jobs\SynchronizeRoles;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Inertia\Response as InertiaResponse;
use Ometra\Caronte\AppBoundRequest;
use Nette\Utils\Json;

class CaronteController extends Controller
{
    protected function toView(string $viewPath, mixed $data): View | InertiaResponse
    {
        if (config('caronte.USE_INERTIA')) {
            return inertia($viewPath, $data);
        } else {
            return view('caronte::' . $viewPath)
                ->with($data);
        }
    }
    /**
     * Show the login form view.
     *
     * @param Request $request HTTP request object.
     * @return View Login form view.
     */
    public function loginForm(Request $request): View | InertiaResponse
    {
        $login_view = config('caronte.USE_2FA') ? '2fa-login' : 'login';

        return $this->toView($login_view, [
            'callback_url' => $request->callback_url,
        ]);
    }

    /**
     * Handle the login request, using 2FA if enabled.
     *
     * @param Request $request HTTP request instance.
     * @return Response|RedirectResponse
     */
    public function login(Request $request): Response|RedirectResponse
    {
        if (config('caronte.USE_2FA')) {
            return CaronteRequest::twoFactorTokenRequest(request: $request);
        }

        return CaronteRequest::userPasswordLogin(request: $request);
    }

    /**
     * Log in the user using a two-factor authentication token.
     *
     * @param Request $request HTTP request object.
     * @param string $token Two-factor authentication token.
     * @return Response|RedirectResponse
     */
    public function twoFactorTokenLogin(Request $request, string $token): Response|RedirectResponse
    {
        return CaronteRequest::twoFactorTokenLogin(request: $request, token: $token);
    }

    /**
     * Show the password recovery request form.
     *
     * @return View Password recovery request form view.
     */
    public function passwordRecoverRequestForm(): View|InertiaResponse
    {
        return $this->toView('password-recover-request', []);
    }

    /**
     * Handle the password recovery request.
     *
     * @param Request $request HTTP request with recovery details.
     * @return Response|RedirectResponse
     */
    public function passwordRecoverRequest(Request $request): Response|RedirectResponse
    {
        return CaronteRequest::passwordRecoverRequest(request: $request);
    }

    /**
     * Validate the password recovery token.
     *
     * @param Request $request HTTP request instance.
     * @param string $token Password recovery token.
     * @return Response|RedirectResponse|View
     */
    public function passwordRecoverTokenValidation(Request $request, string $token): Response|RedirectResponse|View
    {
        return CaronteRequest::passwordRecoverTokenValidation(token: $token);
    }

    /**
     * Handle the password recovery process.
     *
     * @param Request $request HTTP request instance.
     * @param string $token Password recovery token.
     * @return Response|RedirectResponse
     */
    public function passwordRecover(Request $request, string $token): Response|RedirectResponse
    {
        return CaronteRequest::passwordRecover(request: $request, token: $token);
    }

    /**
     * Log out the user.
     *
     * @param Request $request HTTP request object.
     * @return Response|RedirectResponse
     */
    public function logout(Request $request): Response|RedirectResponse
    {
        return CaronteRequest::logout(logout_all_sessions: $request->filled('all'));
    }

    /**
     * Retrieve a token from the Caronte service.
     *
     * @return Response Response containing the token.
     */
    public function getToken(): Response
    {
        return Response(Caronte::getToken()->toString(), 200);
    }

    public function setMetadata(Request $request): Response|RedirectResponse
    {
        return CaronteRequest::setMetadata(request: $request);
    }

    public function synchronizeData(Request $request): JsonResponse
    {
        SynchronizeRoles::dispatch();
        return response()->json(['success' => true]);
    }

    /**
     * Show the management view.
     *
     * @param Request $request HTTP request object.
     * @return View management form view.
     */
    public function managementApp(Request $request): View
    {
        $users = AppBoundRequest::showUsers("", true);
        $users = json_decode($users['data'], true);
        $login_view = config('caronte.MANAGEMENT_VIEW');
        return $this->toView($login_view, [
            'callback_url' => $request->callback_url,
            'users' => $users,
        ]);
    }
}
