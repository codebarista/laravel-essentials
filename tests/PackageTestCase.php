<?php

declare(strict_types=1);

namespace Codebarista\LaravelEssentials\Tests;

use Codebarista\LaravelEssentials\EssentialsServiceProvider;
use Orchestra\Testbench\TestCase;

abstract class PackageTestCase extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            EssentialsServiceProvider::class,
        ];
    }
}
