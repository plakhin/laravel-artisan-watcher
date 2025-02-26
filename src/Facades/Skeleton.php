<?php

declare(strict_types=1);

namespace Plakhin\LPSkeleton\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Plakhin\LPSkeleton\Skeleton
 *
 * @method static string foo()
 */
final class Skeleton extends Facade
{
    public static function getFacadeAccessor(): string
    {
        return \Plakhin\LPSkeleton\Skeleton::class;
    }
}
