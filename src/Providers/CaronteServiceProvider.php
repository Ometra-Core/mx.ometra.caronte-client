<?php

/**
 * @author Gabriel Ruelas
 * @license MIT
 * @version 1.3.2
 * gruelas@gruelasjr
 *
 */

namespace Ometra\Caronte\Providers;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Ometra\Caronte\Facades\Caronte;
use Ometra\Caronte\Console\Commands\NotifyClientConfigurationCommand;
use Equidna\Toolkit\Exceptions\ConflictException;
use Ometra\Caronte\Commands\AttachedRoles;
use Ometra\Caronte\Commands\CrudRoles\CreateRole;
use Ometra\Caronte\Commands\CrudUsers\CreateUser;
use Ometra\Caronte\Commands\CrudRoles\DeleteRole;
use Ometra\Caronte\Commands\CrudRoles\ShowRoles;
use Ometra\Caronte\Commands\CrudRoles\UpdateRole;
use Ometra\Caronte\Commands\CrudUsers\DeleteRolesUser;
use Ometra\Caronte\Commands\CrudUsers\UpdateUser;
use Ometra\Caronte\Commands\CrudUsers\ShowRolesByUser;
use Ometra\Caronte\Commands\ManagementCaronte;
use Ometra\Caronte\Commands\ManagementRoles;
use Ometra\Caronte\Commands\ManagementUsers;
use GuzzleHttp\Promise\Create;
use Inertia\Inertia;

class CaronteServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(
            Caronte::class,
            fn() => new Caronte()
        );

        $this->mergeConfigFrom(__DIR__ . '/../config/caronte.php', 'caronte');
        $this->mergeConfigFrom(__DIR__ . '/../config/caronte-roles.php', 'caronte-roles');
        $this->mergeConfigFrom(
            __DIR__ . '/../config/caronte-jobs.php',
            'caronte-jobs'
        );
    }

    public function boot(Router $router)
    {
        $this->validateCaronteConfig();

        //Registers the Caronte alias and facade.
        $loader = AliasLoader::getInstance();
        $loader->alias('Caronte', \Ometra\Caronte\Facades\Caronte::class);
        $loader->alias('PermissionHelper', \Ometra\Caronte\Helpers\PermissionHelper::class);

        //Registers the middleware
        $router->aliasMiddleware('Caronte.ValidateSession', \Ometra\Caronte\Http\Middleware\ValidateSession::class);
        $router->aliasMiddleware('Caronte.ValidateRoles', \Ometra\Caronte\Http\Middleware\ValidateRoles::class);

        //Registers the base Routes for clients
        Route::prefix(config('caronte.ROUTES_PREFIX'))->middleware(['web'])->group(
            function () {
                $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
            }
        );

        // Registers the API Routes for the package
        Route::prefix('api/' . config('caronte.ROUTES_PREFIX'))->middleware(['api'])->group(
            function () {
                $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
            }
        );

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'caronte');
        $this->loadMigrationsFrom(__DIR__ . '/../migrations');

        //Config for roles
        $this->publishes(
            [
                __DIR__ . '/../config/caronte-roles.php' => config_path('caronte-roles.php'),
            ],
            [
                'caronte:roles',
                'caronte',
            ]
        );

        //Jobs
        $this->publishes([
            __DIR__ . '/../config/caronte-jobs.php' => config_path('caronte-jobs.php'),
        ], 'caronte-jobs');

        //Views
        $this->publishes(
            [
                __DIR__ . '/../resources/views' => resource_path('views/vendor/caronte'),
            ],
            [
                'caronte:views',
                'caronte',
            ]
        );

        //Assets
        $this->publishes(
            [
                __DIR__ . '/../resources/assets' => public_path('vendor/caronte'),
            ],
            [
                'caronte-assets',
                'caronte',
            ]
        );

        //Migrations
        $this->publishes(
            [
                __DIR__ . '/../migrations' => database_path('migrations'),
            ],
            [
                'caronte:migrations',
                'caronte'
            ]
        );

        //Commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                NotifyClientConfigurationCommand::class,
                AttachedRoles::class,
                ManagementRoles::class,
                ManagementUsers::class,
                CreateRole::class,
                UpdateRole::class,
                DeleteRole::class,
                ShowRoles::class,
                CreateUser::class,
                DeleteRolesUser::class,
                UpdateUser::class,
                ShowRolesByUser::class,
                ManagementCaronte::class,
            ]);
        }


        Inertia::share('caronte_user', function () {
            try {
                if (Caronte::checkToken()) {
                    return Caronte::getUser();
                }
            } catch (\Exception $e) {
                return null;
            }

            return null;
        });
    }

    /**
     * Validates required Caronte config values and fails early with a clear message.
     *
     * @throws \RuntimeException
     */
    protected function validateCaronteConfig(): void
    {
        $required = [
            'caronte.URL',
            'caronte.VERSION',
            'caronte.APP_ID',
            'caronte.APP_SECRET',
            'caronte.LOGIN_URL',
        ];

        $missing = [];
        foreach ($required as $key) {
            $value = config($key);
            if (is_null($value) || $value === '') {
                $missing[] = $key;
            }
        }

        if (config('caronte.ENFORCE_ISSUER')) {
            if (empty(config('caronte.ISSUER_ID'))) {
                $missing[] = 'caronte.ISSUER_ID';
            }
        }

        if (!empty($missing)) {
            $msg = "Caronte: Missing required configuration: " . implode(', ', $missing) . ". Please check your .env and config/caronte.php.";
            throw new ConflictException($msg);
        }
    }
}
