<?php declare(strict_types = 1);

namespace App\Console;

use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    // @phpcs:disable SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingAnyTypeHint

    /**
     * Available commands
     */
    protected $commands = [
        \App\Console\Commands\ListRoutesCommand::class,
    ];
}
