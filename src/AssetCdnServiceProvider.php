<?php

namespace Arubacao\AssetCdn;

use Arubacao\AssetCdn\Commands\EmptyCommand;
use Arubacao\AssetCdn\Commands\GenerateWebPackAssetCommand;
use Arubacao\AssetCdn\Commands\PushCommand;
use Arubacao\AssetCdn\Commands\SyncCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class AssetCdnServiceProvider extends PackageServiceProvider
{
    public function registeringPackage()
    {
        $this->app->singleton(Finder::class, function ($app) {
            return new Finder(new Config($app->make('config'), $app->make('path.public')));
        });

        $this->mergeConfigFrom(__DIR__.'/../config/asset-cdn.php', 'asset-cdn');

    }


    public function configurePackage(Package $package): void
    {
        $package->name('asset-cdn')
            ->hasCommands([
                PushCommand::class,
                SyncCommand::class,
                EmptyCommand::class,
                GenerateWebPackAssetCommand::class
            ])
            ->hasConfigFile('asset-cdn');
    }

}
