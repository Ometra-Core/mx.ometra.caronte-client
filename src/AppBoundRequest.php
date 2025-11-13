<?php

namespace Equidna\Caronte;

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

    public static function showRoles(): JsonResponse|RedirectResponse
    {
        try {
            $caronte_response = HTTP::withHeaders(
                [
                    'Authorization' => "Token " . AppBound::getToken(),
                    'Accept' => 'application/json',
                ]
            )->get(
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

        return ResponseHelper::success(
            message: 'ok',
            data: $response,
            forward_url: null
        );
    }

    public static function showUsers(string $paramSearch): JsonResponse|RedirectResponse
    {
        try {
            $caronte_response = HTTP::withHeaders(
                [
                    'Authorization' => "Token " . AppBound::getToken(),
                    'Accept' => 'application/json',
                ]
            )->get(
                config('caronte.URL') . 'api/app/users/',
                [
                    'search' => $paramSearch,
                ]
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

        return ResponseHelper::success(
            message: 'ok',
            data: $response,
            forward_url: null
        );
    }

    public static function assignRoleToUser(string $uriUser, string $uriApplicationRole): JsonResponse|RedirectResponse
    {
        try {
            $caronte_response = HTTP::withHeaders(
                [
                    'Authorization' => "Token " . AppBound::getToken(),
                    'Accept' => 'application/json',
                ]
            )->post(
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
            throw new BadRequestException(
                message: $e->getMessage(),
                previous: $e
            );
        }

        return ResponseHelper::success(
            message: 'ok',
            data: $response,
            forward_url: null
        );
    }

    public static function createRole(string $name, string $description): JsonResponse|RedirectResponse
    {
        try {
            $caronte_response = HTTP::withHeaders(
                [
                    'Authorization' => "Token " . AppBound::getToken(),
                    'Accept' => 'application/json',
                ]
            )->post(
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
            // throw new BadRequestException(
            //     message: $e->getMessage(),
            //     previous: $e
            // );

            return ResponseHelper::success(
                message: 'error',
                data: $response ?? '',
                forward_url: null
            );
        }

        return ResponseHelper::success(
            message: 'ok',
            data: $response ?? '',
            forward_url: null
        );
    }

    public static function updateRole(string $uriApplicationRole, string $description): JsonResponse|RedirectResponse
    {
        try {
            $caronte_response = HTTP::withHeaders(
                [
                    'Authorization' => "Token " . AppBound::getToken(),
                    'Accept' => 'application/json',
                ]
            )->put(
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
            throw new BadRequestException(
                message: $e->getMessage(),
                previous: $e
            );
        }

        return ResponseHelper::success(
            message: 'ok',
            data: $response,
            forward_url: null
        );
    }

    public static function deleteRole(string $uriApplicationRole): JsonResponse|RedirectResponse
    {
        try {
            $caronte_response = HTTP::withHeaders(
                [
                    'Authorization' => "Token " . AppBound::getToken(),
                    'Accept' => 'application/json',
                ]
            )->delete(
                config('caronte.URL') . 'api/app/applications/' . AppBound::getAppId() . '/roles/' . $uriApplicationRole
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

        return ResponseHelper::success(
            message: 'ok',
            data: $response,
            forward_url: null
        );
    }
}
