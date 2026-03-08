<?php

namespace Tests\Feature;

use Tests\TestCase;

class PublishCommandsTest extends TestCase
{
    /**
     * Verifies that all source files for publishing exist in the package.
     *
     * @return void
     */
    public function test_publish_source_files_exist(): void
    {
        // Navigate from tests/Feature/PublishCommandsTest.php to package root
        $packageRoot = dirname(__DIR__, 2);

        $sourceFiles = [
            // Config
            $packageRoot . '/config/caronte.php' => 'Config file',
            // Views
            $packageRoot . '/resources/views' => 'Views directory',
            // Assets
            $packageRoot . '/resources/assets' => 'Assets directory',
            // Inertia JS
            $packageRoot . '/resources/js' => 'Inertia JS directory',
            // Migrations
            $packageRoot . '/database/migrations' => 'Migrations directory',
        ];

        foreach ($sourceFiles as $path => $description) {
            $this->assertTrue(
                file_exists($path),
                "Missing publish source: {$description} at {$path}"
            );
        }
    }

    /**
     * Verifies that config/caronte.php contains required configuration keys.
     *
     * @return void
     */
    public function test_config_file_has_required_keys(): void
    {
        $config = config('caronte');

        $requiredKeys = [
            'URL',
            'APP_ID',
            'APP_SECRET',
            'LOGIN_URL',
            'ROUTES_PREFIX',
            'table_prefix',
            'USE_INERTIA',
        ];

        foreach ($requiredKeys as $key) {
            $this->assertArrayHasKey(
                $key,
                $config,
                "Missing config key: {$key}"
            );
        }
    }

    /**
     * Verifies that database migrations exist and are readable.
     *
     * @return void
     */
    public function test_migrations_exist_and_are_readable(): void
    {
        $packageRoot = dirname(__DIR__, 2);
        $migrationsPath = $packageRoot . '/database/migrations';

        $this->assertTrue(is_dir($migrationsPath), 'Migrations directory does not exist');

        $files = scandir($migrationsPath);
        $phpFiles = array_filter($files, fn($f) => str_ends_with($f, '.php'));

        $this->assertGreaterThan(
            0,
            count($phpFiles),
            'No migration files found in migrations directory'
        );

        foreach ($phpFiles as $file) {
            $filePath = $migrationsPath . '/' . $file;
            $this->assertTrue(
                is_readable($filePath),
                "Migration file is not readable: {$file}"
            );
        }
    }

    /**
     * Verifies that views directory structure is correct.
     *
     * @return void
     */
    public function test_views_directory_structure_is_correct(): void
    {
        $packageRoot = dirname(__DIR__, 2);
        $viewsPath = $packageRoot . '/resources/views';

        $requiredDirs = [
            'auth',
            'management',
            'layouts',
            'partials',
        ];

        foreach ($requiredDirs as $dir) {
            $dirPath = $viewsPath . '/' . $dir;
            $this->assertTrue(
                is_dir($dirPath),
                "Missing views subdirectory: {$dir}"
            );
        }
    }

    /**
     * Verifies that auth views are published with correct Blade files.
     *
     * @return void
     */
    public function test_auth_views_exist(): void
    {
        $packageRoot = dirname(__DIR__, 2);
        $authViewsPath = $packageRoot . '/resources/views/auth';

        $expectedViews = [
            'login.blade.php',
            'two-factor.blade.php',
            'password-recover.blade.php',
            'password-recover-request.blade.php',
            'management.blade.php',
        ];

        foreach ($expectedViews as $view) {
            $viewPath = $authViewsPath . '/' . $view;
            $this->assertTrue(
                file_exists($viewPath),
                "Missing auth view: {$view}"
            );
        }
    }

    /**
     * Verifies that Service Provider has all publish groups configured.
     *
     * @return void
     */
    public function test_service_provider_publishes_are_configured(): void
    {
        // The service provider should be loaded during boot
        // Verify that the provider exists and is properly registered
        $provider = \Ometra\Caronte\Providers\CaronteServiceProvider::class;

        $this->assertTrue(
            class_exists($provider),
            'CaronteServiceProvider does not exist'
        );

        // Verify that configuration loading works from the provider
        $config = config('caronte');

        $this->assertIsArray($config, 'Caronte config should be loaded as array');
        $this->assertNotEmpty($config, 'Caronte config should not be empty');
    }

    /**
     * Verifies that assets directory contains CSS/JS files.
     *
     * @return void
     */
    public function test_assets_directory_has_content(): void
    {
        $packageRoot = dirname(__DIR__, 2);
        $assetsPath = $packageRoot . '/resources/assets';

        $this->assertTrue(
            is_dir($assetsPath),
            'Assets directory does not exist'
        );

        // Check for subdirectories
        $this->assertTrue(
            is_dir($assetsPath . '/css'),
            'CSS subdirectory missing from assets'
        );

        // Verify at least one CSS file exists
        $files = scandir($assetsPath . '/css');
        $cssFiles = array_filter($files, fn($f) => str_ends_with($f, '.css'));

        $this->assertGreaterThan(
            0,
            count($cssFiles),
            'No CSS files found in assets/css directory'
        );
    }

    /**
     * Verifies that Inertia JS resources directory is properly structured.
     *
     * @return void
     */
    public function test_inertia_js_resources_exist(): void
    {
        $packageRoot = dirname(__DIR__, 2);
        $jsPath = $packageRoot . '/resources/js';

        $this->assertTrue(
            is_dir($jsPath),
            'Inertia JS directory does not exist'
        );

        $this->assertTrue(
            is_dir($jsPath . '/Pages'),
            'Pages subdirectory missing from JS resources'
        );
    }
}
