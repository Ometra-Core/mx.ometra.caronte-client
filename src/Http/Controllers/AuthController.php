<?php

/**
 * Authentication controller handling login, logout, and password recovery.
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
use Illuminate\View\View;
use Inertia\Response as InertiaResponse;
use Ometra\Caronte\CaronteRequest;

/**
 * Handles user authentication, two-factor auth, and password recovery flows.
 */
class AuthController extends BaseController
{
    /**
     * Show the login form view.
     *
     * @param  Request             $request  HTTP request object.
     * @return View|InertiaResponse           Login form view.
     */
    public function loginForm(Request $request): View | InertiaResponse
    {
        $login_view = config('caronte.USE_2FA') ? 'auth.two-factor' : 'auth.login';

        return $this->toView($login_view, [
            'callback_url' => $request->callback_url,
            'csrf_token' => csrf_token(),
            'routes' => [
                'login' => route('caronte.login'),
                'twoFactorRequest' => route('caronte.2fa.request'),
                'passwordRecoverForm' => route('caronte.password.recover.form'),
            ],
        ]);
    }

    /**
     * Handle login request with optional two-factor authentication.
     *
     * @param  Request              $request  HTTP request instance.
     * @return Response|RedirectResponse      Auth response or redirect.
     */
    public function login(Request $request): Response|RedirectResponse
    {
        if (config('caronte.USE_2FA')) {
            return CaronteRequest::twoFactorTokenRequest(request: $request);
        }

        return CaronteRequest::userPasswordLogin(request: $request);
    }

    /**
     * Log in user using a two-factor authentication token.
     *
     * @param  Request              $request  HTTP request object.
     * @param  string               $token    Two-factor authentication token.
     * @return Response|RedirectResponse      Auth response or redirect.
     */
    public function twoFactorTokenLogin(Request $request, string $token): Response|RedirectResponse
    {
        return CaronteRequest::twoFactorTokenLogin(request: $request, token: $token);
    }

    /**
     * Show the password recovery request form.
     *
     * @return View|InertiaResponse  Password recovery request form view.
     */
    public function passwordRecoverRequestForm(): View|InertiaResponse
    {
        return $this->toView('auth.password-recover-request', [
            'csrf_token' => csrf_token(),
            'routes' => [
                'passwordRecoverRequest' => route('caronte.password.recover.request'),
            ],
        ]);
    }

    /**
     * Handle password recovery request initiation.
     *
     * @param  Request              $request  HTTP request with recovery details.
     * @return Response|RedirectResponse      Response or redirect.
     */
    public function passwordRecoverRequest(Request $request): Response|RedirectResponse
    {
        return CaronteRequest::passwordRecoverRequest(request: $request);
    }

    /**
     * Validate password recovery token validity.
     *
     * @param  Request              $request  HTTP request instance.
     * @param  string               $token    Password recovery token.
     * @return Response|RedirectResponse|View Response, redirect, or token validation view.
     */
    public function passwordRecoverTokenValidation(Request $request, string $token): Response|RedirectResponse|View
    {
        return CaronteRequest::passwordRecoverTokenValidation(token: $token);
    }

    /**
     * Process password recovery (reset password with valid token).
     *
     * @param  Request              $request  HTTP request instance.
     * @param  string               $token    Password recovery token.
     * @return Response|RedirectResponse      Response or redirect.
     */
    public function passwordRecover(Request $request, string $token): Response|RedirectResponse
    {
        return CaronteRequest::passwordRecover(request: $request, token: $token);
    }

    /**
     * Log out the user.
     *
     * @param  Request              $request  HTTP request object.
     * @return Response|RedirectResponse      Logout response or redirect.
     */
    public function logout(Request $request): Response|RedirectResponse
    {
        return CaronteRequest::logout(logout_all_sessions: $request->filled('all'));
    }
}
