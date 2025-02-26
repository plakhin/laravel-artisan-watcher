<?php

declare(strict_types=1);

namespace Plakhin\LPSkeleton\Tests;

use Plakhin\LPSkeleton\Facades\Skeleton;

test('skeleton', function (): void {
    expect(Skeleton::foo())->toBe('bar');
});
