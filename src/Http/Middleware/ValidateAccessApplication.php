<?php

namespace App\Http\Middleware\Auth;

use Illuminate\Http\Request;
use App\Models\ApiToken;
use Equidna\Toolkit\Helpers\ResponseHelper;
use Carbon\Carbon;
use Closure;

/**
 * Class ValidateAccessToken
 *
 * Middleware to validate the access token in API requests.
 */
class ValidateAccessApplicationToken
{
    /**
     * Handles an incoming request and validates the access token.
     *
     * Checks for the presence of a bearer token, validates it against the database, and ensures it is not expired.
     *
     * @param  Request $request The incoming HTTP request.
     * @param  Closure $next    The next middleware handler.
     * @return mixed           The next middleware response or an unauthorized response.
     */
    public function handle(Request $request, Closure $next)
    {
        $provided_token = $request->bearerToken();
        if (!$provided_token) {
            return ResponseHelper::unauthorized(
                message: 'You stand before the Bifrost without a token. Heimdall sees all, and none may pass unbidden.',
                errors: [
                    'No token provided'
                ]
            );
        }
        $token = ApiToken::find($provided_token);
        if (!$token) {
            return ResponseHelper::unauthorized(
                message: 'Heimdall judges your token unworthy. The Bifrost remains closed to intruders.',
                errors: [
                    'Invalid token'
                ]
            );
        }
        if (Carbon::now()->greaterThan($token->dt_exp)) {
            return ResponseHelper::unauthorized(
                message: 'Your token has faded with the passage of time. Heimdall allows no expired pass through the Bifrost.',
                errors: [
                    'Expired token'
                ]
            );
        }
        $request->attributes->add(['token' => $token]);
        return $next($request);
    }
}
