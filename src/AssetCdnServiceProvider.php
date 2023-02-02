<?php

namespace Arubacao\AssetCdn;

use Arubacao\AssetCdn\Commands\EmptyCommand;
use Arubacao\AssetCdn\Commands\PushCommand;
use Arubacao\AssetCdn\Commands\SyncCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class AssetCdnServiceProvider extends PackageServiceProvider
{

    public function configurePackage(Package $package): void
    {
        $package->name('asset-cdn')
            ->hasCommands([
                PushCommand::class,
                SyncCommand::class,
                EmptyCommand::class
            ])
            ->hasConfigFile('asset-cdn');
    }

}
