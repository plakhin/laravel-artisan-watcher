<?php

declare(strict_types=1);

namespace Plakhin\ArtisanWatcher\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Plakhin\ArtisanWatcher\ArtisanWatcherServiceProvider;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            ArtisanWatcherServiceProvider::class,
        ];
    }
}
