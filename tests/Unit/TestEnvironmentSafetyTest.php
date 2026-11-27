<?php

namespace Tests\Unit;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Tests\TestEnvironmentSafety;

class TestEnvironmentSafetyTest extends TestCase
{
    private static function safeConfig(): array
    {
        return ['app' => ['env' => 'testing', 'key' => 'base64:AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA='], 'database' => ['default' => 'sqlite', 'connections' => ['sqlite' => ['driver' => 'sqlite', 'database' => ':memory:', 'url' => null]]], 'cache' => ['default' => 'array'], 'session' => ['driver' => 'array'], 'queue' => ['default' => 'sync'], 'mail' => ['default' => 'array']];
    }

    public function test_safe_memory_config_is_accepted(): void
    {
        TestEnvironmentSafety::assertSafe(self::safeConfig());
        $this->assertTrue(true);
    }

    #[DataProvider('unsafeConfigs')]
    public function test_unsafe_config_is_rejected_without_connecting(array $config): void
    {
        $this->expectException(RuntimeException::class);
        TestEnvironmentSafety::assertSafe($config);
    }

    public static function unsafeConfigs(): iterable
    {
        foreach (['environment', 'key', 'default', 'driver', 'database', 'url', 'read', 'write', 'cache', 'session', 'queue', 'mail'] as $case) {
            $config = self::safeConfig();
            switch ($case) {
                case 'environment': $config['app']['env'] = 'production'; break;
                case 'key': $config['app']['key'] = 'unsafe-sentinel'; break;
                case 'default': $config['database']['default'] = 'mysql'; break;
                case 'driver': $config['database']['connections']['sqlite']['driver'] = 'mysql'; break;
                case 'database': $config['database']['connections']['sqlite']['database'] = 'sentinel.sqlite'; break;
                case 'url': $config['database']['connections']['sqlite']['url'] = 'sqlite:///sentinel.sqlite'; break;
                case 'read': $config['database']['connections']['sqlite']['read'] = ['database' => 'sentinel.sqlite']; break;
                case 'write': $config['database']['connections']['sqlite']['write'] = ['database' => 'sentinel.sqlite']; break;
                case 'cache': $config['cache']['default'] = 'database'; break;
                case 'session': $config['session']['driver'] = 'database'; break;
                case 'queue': $config['queue']['default'] = 'database'; break;
                case 'mail': $config['mail']['default'] = 'smtp'; break;
            }
            yield $case => [$config];
        }
    }

    public function test_cached_configuration_is_rejected_without_reading_it(): void
    {
        $path = tempnam(sys_get_temp_dir(), 'batani-test-');
        try {
            $this->expectException(RuntimeException::class);
            TestEnvironmentSafety::assertNoCachedConfiguration($path);
        } finally {
            unlink($path);
        }
    }
}
