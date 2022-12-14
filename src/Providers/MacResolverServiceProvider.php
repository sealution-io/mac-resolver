<?php

namespace Sealution\MacResolver\Providers;

use Sealution\MacResolver\Console\Commands\DownloadOuiFileFromIeeeWebPage;
use Sealution\MacResolver\Console\Commands\GetMacAddressDetailsCommand;
use Sealution\MacResolver\Console\Commands\GetVendorDetailsCommand;
use Sealution\MacResolver\Console\Commands\InstallPackageCommand;
use Sealution\MacResolver\Console\Commands\SeedTableFromOuiFile;
use Illuminate\Support\ServiceProvider;
use Sealution\MacResolver\Console\Commands\SyncMacDefinitionsCommand;

class MacResolverServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishConfig();

            $this->publishMigrations();

            $this->registerCommands();
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfig();
    }

    /**
     * Automatically apply the package configuration.
     */
    private function mergeConfig()
    {
        $path = $this->getConfigPath();

        $this->mergeConfigFrom($path, 'ieee');
    }

    /**
     * Publish Config File.
     */
    private function publishConfig()
    {
        $path = $this->getConfigPath();

        $this->publishes([
            $path => config_path('ieee.php'),
        ], 'config');
    }

    /**
     * Publish Migrations.
     */
    private function publishMigrations()
    {
        $path = $this->getMigrationsPath();

        $this->publishes([
            $path => database_path('migrations'),
        ], 'migrations');
    }

    /**
     * Registering package commands.
     */
    private function registerCommands()
    {
        if (!file_exists(config_path('ieee.php'))) {
            $this->commands([
                InstallPackageCommand::class,
            ]);
        }

        $this->commands([
            DownloadOuiFileFromIeeeWebPage::class,
            GetMacAddressDetailsCommand::class,
            GetVendorDetailsCommand::class,
            SeedTableFromOuiFile::class,
            SyncMacDefinitionsCommand::class
        ]);
    }

    /**
     * Returns the config file path.
     *
     * @return string
     */
    private function getConfigPath(): string
    {
        return __DIR__.'/../../config/ieee.php';
    }

    /**
     * Returns the migration path.
     *
     * @return string
     */
    private function getMigrationsPath(): string
    {
        return __DIR__.'/../../database/migrations/';
    }
}
