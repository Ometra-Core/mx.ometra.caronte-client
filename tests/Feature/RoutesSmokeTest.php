<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class RoutesSmokeTest extends TestCase
{
    public function test_required_named_routes_are_registered(): void
    {
        $routes = [
            'caronte.login.form',
            'caronte.login',
            'caronte.logout',
            'caronte.2fa.request',
            'caronte.2fa.login',
            'caronte.password.recover.form',
            'caronte.password.recover.request',
            'caronte.password.recover.validate-token',
            'caronte.password.recover.submit',
            'caronte.token',
            'caronte.set-metadata',
            'caronte.management.dashboard',
            'caronte.management.synchronize',
            'caronte.management.users.list',
            'caronte.management.users.store',
            'caronte.management.users.update',
            'caronte.management.users.delete',
            'caronte.management.users.roles.list',
            'caronte.management.users.roles.attach',
            'caronte.management.users.roles.delete',
            'caronte.management.roles.list',
            'caronte.management.roles.create',
            'caronte.management.roles.update',
            'caronte.management.roles.delete',
        ];

        foreach ($routes as $name) {
            $this->assertTrue(Route::has($name), "Missing route: {$name}");
        }
    }

    public function test_auth_routes_resolve_to_expected_controller_methods(): void
    {
        $this->assertStringContainsString('AuthController@loginForm', Route::getRoutes()->getByName('caronte.login.form')->getActionName());
        $this->assertStringContainsString('AuthController@login', Route::getRoutes()->getByName('caronte.login')->getActionName());
        $this->assertStringContainsString('AuthController@twoFactorTokenRequest', Route::getRoutes()->getByName('caronte.2fa.request')->getActionName());
    }

    public function test_login_and_password_recover_form_routes_return_success(): void
    {
        $this->get('/login')->assertOk();
        $this->get('/password/recover')->assertOk();
    }
}
