<?php

declare(strict_types=1);

namespace Plakhin\LPSkeleton;

final class Skeleton
{
    public function foo(): string
    {
        return config()->string('skeleton.foo');
    }
}
