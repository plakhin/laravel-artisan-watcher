<?php

declare(strict_types=1);

namespace Plakhin\LPSkeleton;

use Illuminate\Support\ServiceProvider;

final class LPSkeletonServiceProvider extends ServiceProvider
{
    private const string PACKAGE_NAME = 'skeleton';

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/'.self::PACKAGE_NAME.'.php', self::PACKAGE_NAME);
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/'.self::PACKAGE_NAME.'.php' => config_path(self::PACKAGE_NAME.'php'),
        ], self::PACKAGE_NAME.'-config');

        // $this->loadRoutesFrom(__DIR__.'/../routes/'.self::PACKAGE_NAME.'.php');

        // $this->configureMigrations();
        // $this->configureTranslations();
        // $this->configureViews();
        // $this->configureCommands();
        // $this->configureAssets();
    }

    // private function configureMigrations(): void
    // {
    //     $this->publishesMigrations([
    //         __DIR__.'/../database/migrations' => database_path('migrations'),
    //     ], self::PACKAGE_NAME.'-migrations');
    // }

    // private function configureTranslations(): void
    // {
    //     $this->loadTranslationsFrom(__DIR__.'/../lang', self::PACKAGE_NAME);
    //     $this->publishes([
    //         __DIR__.'/../lang' => $this->app->langPath('vendor/'.self::PACKAGE_NAME),
    //     ], self::PACKAGE_NAME.'-translations');
    // }

    // private function configureViews(): void
    // {
    //     $this->loadViewsFrom(__DIR__.'/../resources/views', self::PACKAGE_NAME);
    //     $this->publishes([
    //         __DIR__.'/../resources/views' => resource_path('views/vendor/'.self::PACKAGE_NAME),
    //     ], self::PACKAGE_NAME.'-views');
    // }

    // private function configureCommands(): void
    // {
    //     if ($this->app->runningInConsole()) {
    //         $this->commands([
    //             //
    //         ]);
    //     }
    // }

    // private function configureAssets(): void
    // {
    //     $this->publishes([
    //         __DIR__.'/../public' => public_path('vendor/'.self::PACKAGE_NAME),
    //     ], self::PACKAGE_NAME.'-assets');
    // }
}
