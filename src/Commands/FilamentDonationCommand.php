<?php

namespace ValentinMorice\FilamentDonation\Commands;

use Illuminate\Console\Command;

class FilamentDonationCommand extends Command
{
    public $signature = 'filament-donation';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
