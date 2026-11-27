<?php

namespace Tests;

use RuntimeException;

final class TestEnvironmentSafety
{
    public static function assertNoCachedConfiguration(string $path): void
    {
        if (file_exists($path)) {
            throw new RuntimeException('Pengujian dihentikan: konfigurasi cache terdeteksi.');
        }
    }

    public static function assertSafe(array $config): void
    {
        $connection = $config['database']['connections']['sqlite'] ?? [];

        if (($config['app']['env'] ?? null) !== 'testing'
            || ($config['app']['key'] ?? null) !== 'base64:AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA='
            || ($config['database']['default'] ?? null) !== 'sqlite'
            || ($connection['driver'] ?? null) !== 'sqlite'
            || ($connection['database'] ?? null) !== ':memory:'
            || ! empty($connection['url'])
            || ! empty($connection['read'])
            || ! empty($connection['write'])
            || ($config['cache']['default'] ?? null) !== 'array'
            || ($config['session']['driver'] ?? null) !== 'array'
            || ($config['queue']['default'] ?? null) !== 'sync'
            || ($config['mail']['default'] ?? null) !== 'array') {
            throw new RuntimeException('Pengujian dihentikan: konfigurasi testing tidak terisolasi.');
        }
    }
}
