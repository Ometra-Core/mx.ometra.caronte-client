<?php

/**
 * Caronte Client Configuration.
 *
 * All settings are configurable via environment variables.
 * See .env.example for complete list of available options.
 *
 * PHP 8.1+
 *
 * @package   Ometra\Caronte
 * @author    Gabriel Ruelas <gruelas@gruelas.com>
 * @license   https://opensource.org/licenses/MIT MIT License
 * @link      https://github.com/Ometra-Core/mx.ometra.caronte-client Documentation
 */

return [
    /*
    |--------------------------------------------------------------------------
    | Caronte Server Configuration (Required)
    |--------------------------------------------------------------------------
    */
    'URL'        => env('CARONTE_URL', ''),
    'APP_ID'     => env('CARONTE_APP_ID', ''),
    'APP_SECRET' => env('CARONTE_APP_SECRET', ''),

    /*
    |--------------------------------------------------------------------------
    | JWT Token Configuration (Required for JWT verification)
    |--------------------------------------------------------------------------
    */
    'ISSUER_ID'      => env('CARONTE_ISSUER_ID', ''),
    'ENFORCE_ISSUER' => env('CARONTE_ENFORCE_ISSUER', true),

    /*
    |--------------------------------------------------------------------------
    | Authentication Features (Optional)
    |--------------------------------------------------------------------------
    */
    'USE_2FA' => env('CARONTE_2FA', false),
    'ALLOW_HTTP_REQUESTS' => env('CARONTE_ALLOW_HTTP_REQUESTS', false),

    /*
    |--------------------------------------------------------------------------
    | Route Configuration
    |--------------------------------------------------------------------------
    */
    'ROUTES_PREFIX' => env('CARONTE_ROUTES_PREFIX', ''),
    'SUCCESS_URL'   => env('CARONTE_SUCCESS_URL', '/'),
    'LOGIN_URL'     => env('CARONTE_LOGIN_URL', '/login'),

    /*
    |--------------------------------------------------------------------------
    | User Management
    |--------------------------------------------------------------------------
    */
    'UPDATE_LOCAL_USER' => env('CARONTE_UPDATE_LOCAL_USER', false),

    /*
    |--------------------------------------------------------------------------
    | View & UI Configuration
    |--------------------------------------------------------------------------
    */
    'USE_INERTIA' => env('CARONTE_USE_INERTIA', false),

    /*
    |--------------------------------------------------------------------------
    | Database Table Prefix
    |--------------------------------------------------------------------------
    */
    'table_prefix' => env('CARONTE_TABLE_PREFIX', ''),

];
