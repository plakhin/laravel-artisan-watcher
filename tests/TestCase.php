<?php

declare(strict_types=1);

namespace Plakhin\LPSkeleton\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Plakhin\LPSkeleton\LPSkeletonServiceProvider;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            LPSkeletonServiceProvider::class,
        ];
    }
}
