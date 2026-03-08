<?php

namespace Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Ometra\Caronte\Providers\CaronteServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            CaronteServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('app.key', 'base64:AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA=');

        $app['config']->set('caronte.URL', 'https://caronte.test/');
        $app['config']->set('caronte.APP_ID', 'test-app-id');
        $app['config']->set('caronte.APP_SECRET', 'test-app-secret-with-minimum-length-32');
        $app['config']->set('caronte.LOGIN_URL', '/login');
        $app['config']->set('caronte.ENFORCE_ISSUER', false);
        $app['config']->set('caronte.ROUTES_PREFIX', '');
        $app['config']->set('caronte.USE_INERTIA', false);
    }
}
