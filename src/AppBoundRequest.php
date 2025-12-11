<?php

namespace Ometra\Caronte;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Equidna\Toolkit\Helpers\ResponseHelper;
use Equidna\Toolkit\Exceptions\BadRequestException;
use Exception;

/**
 * This class is responsible for making basic requests to the Caronte server.
 */

class AppBoundRequest
{
    private function __construct()
    {
        //ONLY STATIC METHODS ALLOWED
    }

    //ROLES MANAGEMENT METHODS
    public static function showRoles(): string
    {
        try {
            $caronte_response = HTTP::withHeaders([
                'Authorization' => "Token " . AppBound::getToken(),
                'Accept' => 'application/json',
            ])->get(
                url: config('caronte.URL') . 'api/app/applications/' . AppBound::getAppId() . '/roles',
            );

            if ($caronte_response->failed()) {
                throw new RequestException($caronte_response);
            }

            $response = $caronte_response->body();
        } catch (RequestException | Exception $e) {
            throw new BadRequestException(
                message: $e->getMessage(),
                previous: $e
            );
        }

        return $response;
    }

    public static function showUsers(string $paramSearch, bool $usersApp = false): array
    {
        try {
            $caronte_response = HTTP::withHeaders([
                'Authorization' => "Token " . AppBound::getToken(),
                'Accept' => 'application/json',
            ])->get(
                config('caronte.URL') . 'api/app/users/',
                [
                    'search' => $paramSearch,
                    'app_users' => $usersApp ? 'true' : 'false',
                ]
            );

            if ($caronte_response->failed()) {
                dd($usersApp);
                throw new RequestException($caronte_response);
            }

            $response = $caronte_response->body();
        } catch (RequestException | Exception $e) {
            throw new BadRequestException(
                message: $e->getMessage(),
                previous: $e
            );
        }
        return ['success' => true, 'data' => $response ?? null, 'error' => null];
    }

    public static function assignRoleToUser(string $uriUser, string $uriApplicationRole): array
    {
        try {
            $caronte_response = HTTP::withHeaders([
                'Authorization' => "Token " . AppBound::getToken(),
                'Accept' => 'application/json',
            ])->post(
                config('caronte.URL') . 'api/app/users/roles/' . $uriApplicationRole . '/' . $uriUser,
                [
                    'uri_user' => $uriUser,
                    'uri_applicationRole' => $uriApplicationRole,
                ]
            );

            if ($caronte_response->failed()) {
                throw new RequestException($caronte_response);
            }

            $response = $caronte_response->body();
        } catch (RequestException | Exception $e) {
            return ['success' => false, 'data' => null, 'error' => $e->getMessage()];
        }
        return ['success' => true, 'data' => $response ?? null, 'error' => null];
    }

    public static function createRole(string $name, string $description): array
    {
        try {
            $caronte_response = HTTP::withHeaders([
                'Authorization' => "Token " . AppBound::getToken(),
                'Accept' => 'application/json',
            ])->post(
                config('caronte.URL') . 'api/app/applications/' . AppBound::getAppId() . '/roles',
                [
                    'description' => $description,
                    'name' => $name,
                ]
            );

            if ($caronte_response->failed()) {
                throw new RequestException($caronte_response);
            }
            $response = $caronte_response->body();
        } catch (RequestException | Exception $e) {
            return ['success' => false, 'data' => null, 'error' => $e->getMessage()];
        }
        return ['success' => true, 'data' => $response ?? null, 'error' => null];
    }

    public static function updateRole(string $uriApplicationRole, string $description): array
    {
        try {
            $caronte_response = HTTP::withHeaders([
                'Authorization' => "Token " . AppBound::getToken(),
                'Accept' => 'application/json',
            ])->put(
                config('caronte.URL') . 'api/app/applications/' . AppBound::getAppId() . '/roles/' . $uriApplicationRole,
                [
                    'description' => $description
                ]
            );

            if ($caronte_response->failed()) {
                throw new RequestException($caronte_response);
            }

            $response = $caronte_response->body();
        } catch (RequestException | Exception $e) {
            return ['success' => false, 'data' => null, 'error' => $e->getMessage()];
        }
        return ['success' => true, 'data' => $response ?? null, 'error' => null];
    }

    public static function deleteRole(string $uriApplicationRole): array
    {
        try {
            $caronte_response = HTTP::withHeaders([
                'Authorization' => "Token " . AppBound::getToken(),
                'Accept' => 'application/json',
            ])->delete(
                config('caronte.URL') . 'api/app/applications/' . AppBound::getAppId() . '/roles/' . $uriApplicationRole
            );

            if ($caronte_response->failed()) {
                throw new RequestException($caronte_response);
            }
            $response = $caronte_response->body();
        } catch (RequestException | Exception $e) {
            return ['success' => false, 'data' => null, 'error' => $e->getMessage()];
        }
        return ['success' => true, 'data' => $response ?? null, 'error' => null];
    }

    //USERS MANAGEMENT METHODS
    public static function createUser(string $name, string $email, string $password, string $password_confirmation): array
    {
        try {
            $caronte_response = HTTP::withHeaders([
                'Authorization' => "Token " . AppBound::getToken(),
                'Accept' => 'application/json',
            ])->post(
                config('caronte.URL') . 'api/app/users',
                [
                    'name' => $name,
                    'email' => $email,
                    'password' => $password,
                    'password_confirmation' => $password_confirmation,
                ]
            );
            if ($caronte_response->failed()) {
                throw new RequestException($caronte_response);
            }
            $response = $caronte_response->body();
        } catch (RequestException | Exception $e) {
            return ['success' => false, 'data' => null, 'error' => $e->getMessage()];
        }
        return ['success' => true, 'data' => $response ?? null, 'error' => null];
    }

    public static function updateUser(string $uri_user, string $name): array
    {
        try {
            $caronte_response = HTTP::withHeaders([
                'Authorization' => "Token " . AppBound::getToken(),
                'Accept' => 'application/json',
            ])->put(
                config('caronte.URL') . 'api/app/users/' . $uri_user,
                [
                    'name' => $name
                ]
            );
            if ($caronte_response->failed()) {
                throw new RequestException($caronte_response);
            }
            $response = $caronte_response->body();
        } catch (RequestException | Exception $e) {
            return ['success' => false, 'data' => null, 'error' => $e->getMessage()];
        }
        return ['success' => true, 'data' => $response ?? null, 'error' => null];
    }

    public static function deleteRoleUser(string $uri_user, string $uri_applicationRole): array
    {
        try {
            $caronte_response = HTTP::withHeaders([
                'Authorization' => "Token " . AppBound::getToken(),
                'Accept' => 'application/json',
            ])->delete(
                config('caronte.URL') . 'api/app/users/roles/' . $uri_applicationRole . '/' . $uri_user
            );
            if ($caronte_response->failed()) {
                throw new RequestException($caronte_response);
            }
            $response = $caronte_response->body();
        } catch (RequestException | Exception $e) {
            return ['success' => false, 'data' => null, 'error' => $e->getMessage()];
        }
        return ['success' => true, 'data' => $response ?? null, 'error' => null];
    }

    public static function showRolesUser(string $uri_user): array
    {
        try {
            $caronte_response = HTTP::withHeaders([
                'Authorization' => "Token " . AppBound::getToken(),
                'Accept' => 'application/json',
            ])->get(
                config('caronte.URL') . 'api/app/users/' . $uri_user . '/roles',
            );
            if ($caronte_response->failed()) {
                throw new RequestException($caronte_response);
            }
            $response = $caronte_response->body();
        } catch (RequestException | Exception $e) {
            return ['success' => false, 'data' => null, 'error' => $e->getMessage()];
        }
        return ['success' => true, 'data' => $response ?? null, 'error' => null];
    }

    public static function deleteUser(string $uri_user): array
    {
        try {
            $caronte_response = HTTP::withHeaders([
                'Authorization' => "Token " . AppBound::getToken(),
                'Accept' => 'application/json',
            ])->delete(
                config('caronte.URL') . 'api/app/users/' . $uri_user
            );
            if ($caronte_response->failed()) {
                throw new RequestException($caronte_response);
            }
            $response = $caronte_response->body();
        } catch (RequestException | Exception $e) {
            return ['success' => false, 'data' => null, 'error' => $e->getMessage()];
        }
        return ['success' => true, 'data' => $response ?? null, 'error' => null];
    }
}
