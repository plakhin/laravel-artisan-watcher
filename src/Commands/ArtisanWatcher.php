<?php

declare(strict_types=1);

namespace Plakhin\ArtisanWatcher\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Sleep;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Process\Process;

final class ArtisanWatcher extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'watch
                            {path? : The path to watch (default: current directory)}
                            {--command= : The command to run when files change}
                            {--interval=1 : Polling interval in seconds}
                            {--extensions=php : Comma-separated list of file extensions to watch}
                            {--exclude=vendor,node_modules : Comma-separated list of directories to exclude}
                            {--max-iterations= : Maximum number of polling iterations (for testing)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Watch for file changes and run specified command';

    /**
     * @var array<string, int>
     */
    private array $filesLastModified = [];

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $pathArg = $this->argument('path');
        $path = is_string($pathArg) ? $pathArg : (string) getcwd();

        $commandOption = $this->option('command');
        $command = is_string($commandOption) ? $commandOption : '';

        $interval = (int) $this->option('interval');

        $extensionsOption = $this->option('extensions');
        $extensionsStr = is_string($extensionsOption) ? $extensionsOption : 'php';
        /** @var array<int, string> $extensions */
        $extensions = explode(',', $extensionsStr);

        $excludeOption = $this->option('exclude');
        $excludeStr = is_string($excludeOption) ? $excludeOption : 'vendor,node_modules';
        /** @var array<int, string> $exclude */
        $exclude = explode(',', $excludeStr);

        if ($command === '' || $command === '0') {
            $this->error('The --command option is required.');

            return Command::FAILURE;
        }

        $this->info("Watching for file changes in: {$path}");
        $this->info("Command to run: {$command}");
        $this->info('Press Ctrl+C to stop watching.');

        $this->initializeFileMap($path, $extensions, $exclude);

        // For testing purposes - allows breaking out of the loop
        $iterations = 0;
        $maxIterations = (int) ($this->option('max-iterations') ?: PHP_INT_MAX);

        while ($iterations < $maxIterations) {
            $iterations++;

            if ($this->checkForChanges($path, $extensions, $exclude)) {
                $this->info('Files changed. Running command...');
                $this->executeCommand($command);
            }

            if ($iterations >= $maxIterations) {
                $this->info('Maximum number of iterations reached. Exiting watch mode.');
                break;
            }

            Sleep::sleep($interval);
        }

        return Command::SUCCESS;
    }

    /**
     * Initialize the file modification map.
     *
     * @param  string  $path  Base path to search
     * @param  array<int, string>  $extensions  File extensions to watch
     * @param  array<int, string>  $exclude  Directories to exclude
     */
    private function initializeFileMap(string $path, array $extensions, array $exclude): void
    {
        $finder = $this->createFinder($path, $extensions, $exclude);

        foreach ($finder as $file) {
            $this->filesLastModified[$file->getRealPath()] = $file->getMTime();
        }

        $this->info(sprintf('Watching %d files for changes', count($this->filesLastModified)));
    }

    /**
     * Check for file changes.
     *
     * @param  string  $path  Base path to search
     * @param  array<int, string>  $extensions  File extensions to watch
     * @param  array<int, string>  $exclude  Directories to exclude
     */
    private function checkForChanges(string $path, array $extensions, array $exclude): bool
    {
        $finder = $this->createFinder($path, $extensions, $exclude);
        $changed = false;

        // Check existing files for modifications
        foreach ($finder as $file) {
            $filePath = $file->getRealPath();
            $lastModified = $file->getMTime();

            // New file or modified file
            if (! isset($this->filesLastModified[$filePath]) || $this->filesLastModified[$filePath] !== $lastModified) {
                $changed = true;
                $this->filesLastModified[$filePath] = $lastModified;
            }
        }

        // Check for deleted files
        foreach (array_keys($this->filesLastModified) as $filePath) {
            if (! File::exists($filePath)) {
                unset($this->filesLastModified[$filePath]);
                $changed = true;
            }
        }

        return $changed;
    }

    /**
     * Create a Finder instance for the given parameters.
     *
     * @param  string  $path  Base path to search
     * @param  array<int, string>  $extensions  File extensions to watch
     * @param  array<int, string>  $exclude  Directories to exclude
     */
    private function createFinder(string $path, array $extensions, array $exclude): Finder
    {
        $finder = new Finder;
        $finder->files()->in($path);

        // Add extensions filter
        if ($extensions !== []) {
            $finder->name(array_map(fn (string $ext): string => "*.{$ext}", $extensions));
        }

        // Add exclusions
        foreach ($exclude as $dir) {
            $finder->notPath($dir);
        }

        return $finder;
    }

    /**
     * Execute the specified command.
     */
    private function executeCommand(string $command): void
    {
        $process = Process::fromShellCommandline($command, null, null, null, null);

        $process->setTty(! app()->runningUnitTests());

        $process->run(function (string $type, string $buffer): void {
            $this->output->write($buffer);
        });
    }
}
