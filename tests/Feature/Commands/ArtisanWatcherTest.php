<?php

declare(strict_types=1);

namespace Plakhin\ArtisanWatcher\Tests;

use function Pest\Laravel\artisan;

it('runs', function (): void {
    artisan('watch')->assertSuccessful();
});
