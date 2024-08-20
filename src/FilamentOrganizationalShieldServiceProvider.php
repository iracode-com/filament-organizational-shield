<?php

namespace IracodeCom\FilamentOrganizationalShield;

use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentOrganizationalShieldServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('filament-organizational-shield')
            ->hasConfigFile()
            ->hasTranslations()
            ->hasMigrations([
                'ir_add_organizational_fields_to_users_table',
                'ir_create_profiles_table',
                'ir_create_organizations_table'
            ])
            ->hasCommands($this->getCommands())
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->publishMigrations();
            });
    }

    protected function getCommands(): array
    {
        return [
            Commands\MakeIracodeShieldPublishCommand::class,
        ];
    }
}