<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Bootstrap\LoadConfiguration;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    public function createApplication()
    {
        $app = require Application::inferBasePath().'/bootstrap/app.php';

        TestEnvironmentSafety::assertNoCachedConfiguration($app->getCachedConfigPath());
        // Never load the developer's .env or .env.testing into the test process.
        $app->useEnvironmentPath(sys_get_temp_dir());
        $app->loadEnvironmentFrom('batani-test-'.bin2hex(random_bytes(16)));
        $app->afterBootstrapping(LoadConfiguration::class, function ($app): void {
            if ($app->bound('config_loaded_from_cache') && $app['config_loaded_from_cache']) {
                throw new \RuntimeException('Pengujian dihentikan: konfigurasi cache terdeteksi.');
            }
            TestEnvironmentSafety::assertSafe($app['config']->all());
        });

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }
}
