<?php

namespace Equidna\Caronte;

use Equidna\Toolkit\Exceptions\UnauthorizedException;
use Equidna\Toolkit\Helpers\ResponseHelper;
use Illuminate\Support\Facades\File;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Equidna\Caronte\AppBoundRequest;

class AppBound
{
    public function __construct()
    {
        //
    }

    public static function getToken(): string
    {
        $token = base64_encode(sha1(config('caronte.APP_ID')) . ':' . config('caronte.APP_SECRET'));
        if (is_null($token) || empty($token)) {
            throw new UnauthorizedException('Token not found');
        }

        return $token;
    }

    public static function getAppId(): string
    {
        return sha1(config('caronte.APP_ID'));
    }

    public static function saveSetting($key, $value)
    {
        $path = self::getConfigPath();
        $directoryPath = File::dirname($path);

        if (!File::isDirectory($directoryPath)) {
            File::makeDirectory($directoryPath, 0755, true);
        }

        $data = File::exists($path)
            ? json_decode(File::get($path), true)
            : [];

        $data[$key] = $value;

        File::put($path, json_encode($data, JSON_PRETTY_PRINT));
    }

    public static function getSetting($key)
    {
        $path = self::getConfigPath();

        if (!File::exists($path)) {
            return null;
        }

        $data = json_decode(File::get($path), true);

        return $data[$key] ?? [];
    }

    private static function getConfigPath(): string
    {
        // Esta función apunta al storage de la APLICACIÓN
        return storage_path('app/caronte-client/roles.json');
    }

    public static function showRoles(): JsonResponse|RedirectResponse
    {
        $roles = AppBound::getSetting('roles');
        if (is_null($roles)) {
            $roles = [];
        } else {
            $response = json_encode($roles);
        }

        return ResponseHelper::success(
            message: 'ok',
            data: $response ?? null,
            forward_url: null
        );
    }

    public static function createRole(string $name, string $description): JsonResponse|RedirectResponse
    {
        $newRoles = AppBound::getSetting('newRoles');
        if (is_null($newRoles)) {
            $newRoles = [];
        } else {
            $newRoles[] = [
                'name' => $name,
                'description' => $description,
            ];
            AppBound::saveSetting('newRoles', $newRoles);
            $response = json_encode(['status' => 'success', 'message' => 'Role created locally. It will be synchronized later.']);
        }

        return ResponseHelper::success(
            message: 'ok',
            data: $response ?? '',
            forward_url: null
        );
    }

    public static function updateRole(string $uriApplicationRole, string $description): JsonResponse|RedirectResponse
    {
        $roles = AppBound::getSetting('roles');
        if (is_null($roles)) {
            $roles = [];
        } else {
            $rol = collect($roles)->firstWhere('uri_applicationRole', $uriApplicationRole);
            if ($rol) {
                $rol['description'] = $description;
            }
            $editRoles = AppBound::getSetting('editRoles');
            $editRoles[] = $rol;
            AppBound::saveSetting('editRoles', $editRoles);
            $response = json_encode(['status' => 'success', 'message' => 'Role created locally. It will be synchronized later.']);
        }


        return ResponseHelper::success(
            message: 'ok',
            data: $response,
            forward_url: null
        );
    }

    public static function deleteRole(string $uriApplicationRole): JsonResponse|RedirectResponse
    {
        $roles = AppBound::getSetting('roles');
        if (is_null($roles)) {
            $roles = [];
        } else {
            $rol = collect($roles)->firstWhere('uri_applicationRole', $uriApplicationRole);
            if ($rol) {
                $deleteRoles = AppBound::getSetting('deleteRoles');
                $deleteRoles[] = $rol;
                AppBound::saveSetting('deleteRoles', $deleteRoles);
                $response = json_encode(['status' => 'success', 'message' => 'Role created locally. It will be synchronized later.']);
            }
        }

        return ResponseHelper::success(
            message: 'ok',
            data: $response,
            forward_url: null
        );
    }

    public static function synchronizeRoles(): JsonResponse|RedirectResponse
    {
        $newRoles = AppBound::getSetting('newRoles');
        $editRoles = AppBound::getSetting('editRoles');
        $deleteRoles = AppBound::getSetting('deleteRoles');

        //save roles
        foreach ($newRoles as $roleNew) {
            $response = AppBoundRequest::createRole($roleNew['name'], $roleNew['description']);
            if ($response->getStatusCode() !== 200) {
                //TODO:escribir en el log los errores
            }
        }

        //update roles
        foreach ($editRoles as $roleEdit) {
            $response = AppBoundRequest::updateRole(uriApplicationRole: $roleEdit['uri_applicationRole'], description: $roleEdit['description']);
            if ($response->getStatusCode() !== 200) {
                //TODO:escribir en el log los errores
            }
        }

        //delete roles
        foreach ($deleteRoles as $roleDelete) {
            $response = AppBoundRequest::deleteRole($roleDelete['uri_applicationRole']);
            if ($response->getStatusCode() !== 200) {
                //TODO:escribir en el log los errores
            }
        }

        AppBound::saveSetting('newRoles', []);
        AppBound::saveSetting('editRoles', []);
        AppBound::saveSetting('deleteRoles', []);

        return ResponseHelper::success(
            message: 'ok',
            data: null,
            forward_url: null
        );
    }
}
