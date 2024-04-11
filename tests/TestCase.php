<?php

namespace NickPotts\LibSql\Test;

use Illuminate\Foundation\Testing\RefreshDatabase;
use NickPotts\LibSql\LibSqlServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    use RefreshDatabase;

    public function connection(string $connection = 'libsql')
    {
        return $this->app['db']->connection($connection);
    }

    /**
     * {@inheritdoc}
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->loadLaravelMigrations(['--database' => 'libsql']);

        $this->withFactories(__DIR__ . '/database/factories');
    }

    /**
     * {@inheritdoc}
     */
    public function getEnvironmentSetUp($app)
    {
        $app['config']->set('app.key', 'wslxrEFGWY6GfGhvN9L3wH3KSRJQQpBD');
        $app['config']->set('auth.providers.users.model', Models\User::class);
        $app['config']->set('database.default', 'libsql');

        $app['config']->set('database.connections.libsql', [
            'driver' => 'libsql',
            'prefix' => '',
            'api' => 'http://127.0.0.1:8081',
        ]);
        $app['config']->set('database.connections.second_connection', [
            'driver' => 'libsql',
            'prefix' => '',
            'api' => 'http://127.0.0.1:8081',
        ]);

        $app['config']->set('database.connections.libsql_bad', [
            'driver' => 'libsql',
            'prefix' => '',
            'api' => 'http://127.0.0.1:50',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    protected function getPackageProviders($app)
    {
        return [
            LibSqlServiceProvider::class,
        ];
    }
}
