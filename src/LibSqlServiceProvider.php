<?php

namespace NickPotts\LibSql;

use Illuminate\Support\ServiceProvider;
use NickPotts\LibSql\LibSql\LibSqlConnection;

class LibSqlServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerLibSql();
    }

    /**
     * Register the LibSql service.
     *
     * @return void
     */
    protected function registerLibSql()
    {
        $this->app->resolving('db', function ($db) {
            $db->extend('libsql', function ($config, $name) {
                $config['name'] = $name;

                $connection = new LibSqlConnection(
                    new LibSqlHttpConnector(
                        $config['token'] ?? null,
                        $config['api'] ?? 'http://127.0.0.1:8080',
                    ),
                    $config,
                );
                return $connection;
            });
        });
    }
}
