<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;

class ProcessEnvironmentTest extends TestCase
{
    public function test_conflicting_process_environment_is_overridden_before_bootstrap(): void
    {
        $process = new Process([
            PHP_BINARY,
            'vendor/bin/phpunit',
            '--colors=never',
            '--do-not-cache-result',
            'tests/Feature/TestEnvironmentBootstrapTest.php',
        ], dirname(__DIR__, 2), [
            'APP_ENV' => 'production',
            'APP_KEY' => 'review-sentinel',
            'APP_MAINTENANCE_DRIVER' => 'cache',
            'BROADCAST_CONNECTION' => 'redis',
            'DB_CONNECTION' => 'mysql',
            'DB_DATABASE' => 'review-sentinel.sqlite',
            'DB_URL' => 'sqlite:///review-sentinel.sqlite',
            'CACHE_STORE' => 'database',
            'SESSION_DRIVER' => 'database',
            'QUEUE_CONNECTION' => 'database',
            'MAIL_MAILER' => 'smtp',
        ]);
        $process->setTimeout(60);

        $this->assertSame(0, $process->run(), 'Subproses bootstrap dengan environment sintetis harus berhasil.');
        $this->assertStringContainsString('OK (1 test,', $process->getOutput());
    }
}
