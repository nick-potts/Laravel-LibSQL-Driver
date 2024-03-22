<?php

namespace Squark\LibSqlDriver;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Squark\LibSqlDriver\Database\Connectors\LibSqlConnection;
use Squark\LibSqlDriver\Database\Connectors\LibSqlConnector;

class LibSqlServiceProvider extends PackageServiceProvider
{
    public function register(): void
    {
        LibSqlConnection::resolverFor('libsql', function ($connection, $database, $prefix, $config) {
            return new LibSqlConnection($connection, $database, $prefix, $config);
        });
    }

    public function boot(): void
    {
        $this->app->bind('db.connector.libsql', LibSqlConnector::class);
    }

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('libsql-driver')
            ->hasConfigFile();
    }
}
