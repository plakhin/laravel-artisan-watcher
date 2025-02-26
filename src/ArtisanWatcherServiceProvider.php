<?php

declare(strict_types=1);

namespace Plakhin\ArtisanWatcher;

use Illuminate\Support\ServiceProvider;

final class ArtisanWatcherServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([Commands\ArtisanWatcher::class]);
        }
    }
}
