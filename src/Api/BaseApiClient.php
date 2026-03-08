<?php

/**
 * Base API client for Caronte server communication.
 *
 * Provides shared HTTP request functionality with authentication for all API clients.
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
 * Base class for Caronte API clients.
 *
 * @author Gabriel Ruelas
 * @license MIT
 * @version 1.4.0
 */
abstract class BaseApiClient
{
    protected function __construct()
    {
        //
    }

    /**
     * Makes an authenticated HTTP request to the Caronte server.
     *
     * @param  string $method   HTTP method (get, post, put, delete).
     * @param  string $endpoint API endpoint path (without base URL).
     * @param  array  $data     Request payload data.
     * @return array{success: bool, data: string|null, error: string|null}
     */
    protected static function makeRequest(string $method, string $endpoint, array $data = []): array
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
