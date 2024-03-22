<?php

namespace Squark\LibSqlDriver\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Squark\LibSqlDriver\LibSqlServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

    }

    protected function getPackageProviders($app)
    {
        return [
            LibSqlServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'libsql');
    }
}
