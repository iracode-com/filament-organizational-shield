<?php

namespace IracodeCom\FilamentOrganizationalShield\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

use function Laravel\Prompts\confirm;

class MakeIracodeShieldPublishCommand extends Command
{
    use Concerns\CanManipulateFiles;

    public $signature = 'iracode-shield:install';

    public $description = "Install filament shield's Resource.";

    public function handle(Filesystem $filesystem): int
    {
        $baseResourcePath         = app_path((string) Str::of('Filament\\Resources\\Shield')->replace('\\', '/'));
        $roleResourcePath         = app_path((string) Str::of('Filament\\Resources\\Shield\\RoleResource.php')->replace('\\', '/'));
        $userResourcePath         = app_path((string) Str::of('Filament\\Resources\\Shield\\UserResource.php')->replace('\\', '/'));
        $organizationResourcePath = app_path((string) Str::of('Filament\\Resources\\Shield\\OrganizationResource.php')->replace('\\', '/'));
        $positionResourcePath     = app_path((string) Str::of('Filament\\Resources\\Shield\\PositionResource.php')->replace('\\', '/'));

        if ($this->checkForCollision([$roleResourcePath, $userResourcePath, $organizationResourcePath, $positionResourcePath])) {
            $confirmed = confirm('Shield Resource already exists. Overwrite?');
            if (! $confirmed) {
                return self::INVALID;
            }
        }

        $filesystem->ensureDirectoryExists($baseResourcePath);
        $filesystem->copyDirectory(__DIR__ . '/../Resources', $baseResourcePath);

        $currentNamespace = 'IracodeCom\\FilamentOrganizationalShield\\Resources';
        $newNamespace     = 'App\\Filament\\Resources\\Shield';

        $this->replaceInFile($roleResourcePath, $currentNamespace, $newNamespace);
        $this->replaceInFile($userResourcePath, $currentNamespace, $newNamespace);
        $this->replaceInFile($positionResourcePath, $currentNamespace, $newNamespace);
        $this->replaceInFile($organizationResourcePath, $currentNamespace, $newNamespace);

        $this->replaceInFile($baseResourcePath . '/RoleResource/Pages/CreateRole.php', $currentNamespace, $newNamespace);
        $this->replaceInFile($baseResourcePath . '/RoleResource/Pages/EditRole.php', $currentNamespace, $newNamespace);
        $this->replaceInFile($baseResourcePath . '/RoleResource/Pages/ViewRole.php', $currentNamespace, $newNamespace);
        $this->replaceInFile($baseResourcePath . '/RoleResource/Pages/ListRoles.php', $currentNamespace, $newNamespace);

        $this->replaceInFile($baseResourcePath . '/UserResource/Pages/CreateUser.php', $currentNamespace, $newNamespace);
        $this->replaceInFile($baseResourcePath . '/UserResource/Pages/EditUser.php', $currentNamespace, $newNamespace);
        $this->replaceInFile($baseResourcePath . '/UserResource/Pages/ListUsers.php', $currentNamespace, $newNamespace);
        $this->replaceInFile($baseResourcePath . '/UserResource/RelationManagers/PermissionsRelationManager.php', $currentNamespace, $newNamespace);
        $this->replaceInFile($baseResourcePath . '/UserResource/Schemas/OrganizationSchema.php', $currentNamespace, $newNamespace);
        $this->replaceInFile($baseResourcePath . '/UserResource/Schemas/PermissionSchema.php', $currentNamespace, $newNamespace);
        $this->replaceInFile($baseResourcePath . '/UserResource/Schemas/RoleSchema.php', $currentNamespace, $newNamespace);
        $this->replaceInFile($baseResourcePath . '/UserResource/Schemas/UserManagementSchema.php', $currentNamespace, $newNamespace);

        $this->replaceInFile($baseResourcePath . '/OrganizationResource/Pages/CreateOrganization.php', $currentNamespace, $newNamespace);
        $this->replaceInFile($baseResourcePath . '/OrganizationResource/Pages/EditOrganization.php', $currentNamespace, $newNamespace);
        $this->replaceInFile($baseResourcePath . '/OrganizationResource/Pages/ListOrganizations.php', $currentNamespace, $newNamespace);
        $this->replaceInFile($baseResourcePath . '/OrganizationResource/Widgets/StructureWidget.php', $currentNamespace, $newNamespace);

        $this->replaceInFile($baseResourcePath . '/PositionResource/Pages/CreatePosition.php', $currentNamespace, $newNamespace);
        $this->replaceInFile($baseResourcePath . '/PositionResource/Pages/EditPosition.php', $currentNamespace, $newNamespace);
        $this->replaceInFile($baseResourcePath . '/PositionResource/Pages/ListPositions.php', $currentNamespace, $newNamespace);

        $this->components->info("Shield's Resource have been published successfully!");

        if ($this->confirm('Do you want to publish migrations?', true)) {
            $this->callSilent('vendor:publish', ['--tag' => 'filament-organizational-shield-migrations']);
            $this->info("Migrations have been published successfully!");
        }

        if ($this->confirm('Do you want to publish configs?', true)) {
            $this->callSilent('vendor:publish', ['--tag' => 'filament-organizational-shield-config']);
            $this->info("Config file has been published successfully!");
        }
        if ($this->confirm('Do you want to publish translations?', true)) {
            $this->callSilent('vendor:publish', ['--tag' => 'filament-organizational-shield-translations']);
            $this->info("Translation file has been published successfully!");
        }

        // Shield seeder
        $shieldSeederPath       = database_path('seeders/ShieldSeeder.php');
        $userSeederPath         = database_path('seeders/UserSeeder.php');
        $organizationSeederPath = database_path('seeders/OrganizationSeeder.php');
        if ($this->checkForCollision(paths: [$shieldSeederPath, $userSeederPath, $organizationSeederPath])) {
            $confirmed = confirm('ShieldSeeder already exists. Overwrite?');
            if (! $confirmed) {
                return self::INVALID;
            }
        }
        $this->copyStubToApp(stub: 'ShieldSeeder', targetPath: $shieldSeederPath);
        $this->copyStubToApp(stub: 'UserSeeder', targetPath: $userSeederPath);
        $this->copyStubToApp(stub: 'OrganizationSeeder', targetPath: $organizationSeederPath);


        $this->components->info('ShieldSeeder, UserSeeder, OrganizationSeeder generated successfully.');
        return self::SUCCESS;
    }
}
