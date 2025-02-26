<?php

declare(strict_types=1);

namespace Plakhin\ArtisanWatcher\Commands;

use Illuminate\Console\Command;

final class ArtisanWatcher extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'watch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Watch for FS events and run command';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        return Command::SUCCESS;
    }
}
