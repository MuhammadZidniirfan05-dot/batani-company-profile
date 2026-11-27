<?php

namespace Tests\Feature;

use Tests\TestCase;
use Tests\TestEnvironmentSafety;

class TestEnvironmentBootstrapTest extends TestCase
{
    public function test_bootstrap_uses_isolated_effective_configuration(): void
    {
        TestEnvironmentSafety::assertSafe($this->app['config']->all());

        $this->assertSame('testing', config('app.env'));
        $this->assertSame('sqlite', config('database.default'));
        $this->assertSame(':memory:', config('database.connections.sqlite.database'));
        $this->assertSame('', config('database.connections.sqlite.url'));
        $this->assertSame('array', config('cache.default'));
        $this->assertSame('array', config('session.driver'));
        $this->assertSame('sync', config('queue.default'));
        $this->assertSame('array', config('mail.default'));
        $this->assertSame('file', config('app.maintenance.driver'));
        $this->assertNull(config('broadcasting.default'));
        $this->assertSame([], $this->app['db']->getConnections());
        $this->get('/up')->assertOk();
    }
}
