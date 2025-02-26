<?php

declare(strict_types=1);

namespace Plakhin\ArtisanWatcher\Tests;

use Carbon\CarbonInterval;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Sleep;

use function Pest\Laravel\artisan;

it('fails when no command is specified', function (): void {
    artisan('watch')
        ->expectsOutput('The --command option is required.')
        ->assertFailed();
});

it('accepts command option and runs through multiple iterations', function (): void {
    Sleep::fake();

    // Run with max-iterations=3 to break out of the loop after three iterations
    artisan('watch', [
        '--command' => 'echo "test"',
        '--max-iterations' => 3,
    ])
        ->assertSuccessful()
        ->expectsOutput('Watching for file changes in: '.getcwd())
        ->expectsOutput('Command to run: echo "test"')
        ->expectsOutput('Press Ctrl+C to stop watching.')
        ->expectsOutput('Maximum number of iterations reached. Exiting watch mode.');

    // Verify that Sleep was called with the interval parameter
    Sleep::assertSlept(fn (CarbonInterval $duration): bool => $duration->seconds === 1, 2);
});

it('handles file deletion correctly', function (): void {
    Sleep::fake();

    // Setup a fake iterator that will include items for the first call
    // and then a different set for the second call (simulating deletion)
    $tempdir = sys_get_temp_dir().'/artisan-watcher-test-'.uniqid();
    mkdir($tempdir);
    $tempFile = $tempdir.'/test-'.uniqid().'.php';
    file_put_contents($tempFile, '<?php echo "test"; ?>');

    try {
        // We need to mock out the file checks to simulate file deletion
        File::shouldReceive('exists')
            ->andReturnUsing(function ($path): bool {
                // First call returns true, second call returns false
                static $calls = 0;
                $calls++;

                return $calls === 1;
            });

        // Run with special parameter for mock
        artisan('watch', [
            'path' => $tempdir,
            '--command' => 'echo "test-delete"',
            '--max-iterations' => 2,
        ])->assertSuccessful()
            ->expectsOutput('Watching for file changes in: '.$tempdir)
            ->expectsOutput('Command to run: echo "test-delete"')
            ->expectsOutput('Press Ctrl+C to stop watching.')
            ->expectsOutput('Watching 1 files for changes')
            ->expectsOutput('Files changed. Running command...')
            ->expectsOutputToContain('test-delete')
            ->expectsOutput('Maximum number of iterations reached. Exiting watch mode.');
    } finally {
        if (file_exists($tempFile)) {
            unlink($tempFile);
        }
        if (file_exists($tempdir)) {
            rmdir($tempdir);
        }
    }
});
