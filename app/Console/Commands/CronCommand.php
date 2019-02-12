<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class CronCommand extends Command
{
    protected $signature = 'rpc:cron';
    protected $description = 'The cron task for managing the keystore cache expiry.';

    public function handle()
    {
        $expiry = \DB::query()
            ->select(['value'])
            ->from('wallet_configurations')
            ->where('id', '=', 'expiry')
            ->first()->value;

        if ($expiry > 0 && $expiry < Carbon::now()) {
            // Remove the cached password
            Storage::put('app/keystore', '');

            // Reset the expiry date
            \DB::query()->from('wallet_configurations')->where('id', 'expiry')->update(['val' => 0]);
        }
    }
}
