<?php

namespace VcMeet\Jitsi\Tests;

use VcMeet\Jitsi\JitsiServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            JitsiServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        config()->set('database.default', 'testing');
        config()->set('database.connections.testing', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
        
        // Set up package configuration
        config()->set('meeting.default_duration_minutes', 60);
        config()->set('meeting.code_length', 10);
    }
}
