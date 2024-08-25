# Filament Organizational Shield

### bezhanSalleh/filament-shield for Filament with organizational functionality

This package provides some Filament resources to add organizational permissions for acl based on `bezhanSalleh/filament-shield` package

## Requirements

-   Laravel v11
-   Filament v3
-   bezhanSalleh/filament-shield v3

## Languages Supported

Filament Organizational Shield Plugin is translated for :

-   us English
-   fa Farsi

## Installation

You can install the package via composer:

```bash
$ composer require iracode-com/filament-organizational-shield
```

After that publish the Spatie PermissionServiceProvider

```bash
php artisan vendor:publish --provider=Spatie\Permission\PermissionServiceProvider
```

Second, publish the plugin filament resources, migrations, config file and translation

```bash
php artisan iracode-shield:install
```

Then, modify your User eloquent model with the following sample:

`App\Models\User.php`
```php
namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use IracodeCom\FilamentOrganizationalShield\Enums\RoleEnum;
use IracodeCom\FilamentOrganizationalShield\Enums\UserRole;
use IracodeCom\FilamentOrganizationalShield\Models\Organization;
use IracodeCom\FilamentOrganizationalShield\Models\Profile;
use IracodeCom\FilamentOrganizationalShield\Models\UserOrganizationalInformation;
use IracodeCom\FilamentOrganizationalShield\Observers\UserObserver;
use IracodeCom\FilamentOrganizationalShield\Traits\HasShieldPermissions;
use BezhanSalleh\FilamentShield\Traits\HasPanelShield;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Panel;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Traits\HasRoles;

#[ObservedBy(UserObserver::class)]
class User extends Authenticatable implements FilamentUser, HasAvatar
{
    use HasFactory, Notifiable, HasRoles, HasPanelShield, HasShieldPermissions;

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar_url',
        'national_code',
        'role',
        'ip',
        'agent',
        'last_login',
        'banned_until',
        'must_password_reset',
        'can_password_reset',
        'password_never_expires',
        'status',
        'prefers_bale',
        'prefers_telegram',
        'prefers_sms',
        'telegram_chat_id',
        'bale_chat_id',
        'phone'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'banned_until'      => 'datetime',
            'password'          => 'hashed',
            'role'              => UserRole::class
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role == UserRole::ADMIN;
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole([RoleEnum::SUPER_ADMIN, RoleEnum::ADMIN]);
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->isAdmin() && $this->status == 1;
    }

    public function canResetUsersPassword(): bool
    {
        return $this->isAdmin() && $this->isSuperAdmin();
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->avatar_url ? Storage::url($this->avatar_url) : null;
    }

    public function organizationalPermission(): bool
    {
        return (bool) $this->organizationalInformation?->structure_id;
    }

    // Relationships

    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }

    public function organizationalInformation(): HasOne
    {
        return $this->hasOne(UserOrganizationalInformation::class);
    }

    public function organizations(): BelongsToMany
    {
        return $this->belongsToMany(Organization::class, table: 'ar_organization_user');
    }
}

```

`ShieldSeeder`

```php
namespace Database\Seeders;

use BezhanSalleh\FilamentShield\Facades\FilamentShield;
use Illuminate\Database\Seeder;
use BezhanSalleh\FilamentShield\Support\Utils;
use Illuminate\Support\Arr;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class ShieldSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $rolesWithPermissions = json_encode($this->roles());
        $directPermissions    = json_encode($this->permissions());

        static::makeRolesWithPermissions($rolesWithPermissions);
        static::makeDirectPermissions($directPermissions);

        $this->command->info('Shield Seeding Completed.');
    }

    protected static function makeRolesWithPermissions(string $rolesWithPermissions): void
    {
        if (! blank($rolePlusPermissions = json_decode($rolesWithPermissions, true))) {
            $roleModel       = Utils::getRoleModel();
            $permissionModel = Utils::getPermissionModel();

            foreach ($rolePlusPermissions as $rolePlusPermission) {
                $role = $roleModel::firstOrCreate([
                    'name'       => $rolePlusPermission['name'],
                    'guard_name' => $rolePlusPermission['guard_name'],
                ]);

                if (! blank($rolePlusPermission['permissions'])) {
                    $permissionModels = collect($rolePlusPermission['permissions'])
                        ->map(fn($permission) => $permissionModel::firstOrCreate([
                            'name'       => $permission,
                            'guard_name' => $rolePlusPermission['guard_name'],
                        ]))
                        ->all();

                    $role->syncPermissions($permissionModels);
                }
            }
        }
    }

    public static function makeDirectPermissions(string $directPermissions): void
    {
        if (! blank($permissions = json_decode($directPermissions, true))) {
            $permissionModel = Utils::getPermissionModel();

            foreach ($permissions as $permission) {
                if ($permissionModel::whereName($permission)->doesntExist()) {
                    $permissionModel::create([
                        'name'       => $permission,
                        'guard_name' => 'web'
                    ]);
                }
            }
        }
    }

    private function roles(): array
    {
        return [
            ['name' => 'panel_user', 'guard_name' => 'web', 'permissions' => []],
            ['name' => 'super_admin', 'guard_name' => 'web', 'permissions' => []],
            ['name' => 'admin', 'guard_name' => 'web', 'permissions' => []],
            ['name' => 'user', 'guard_name' => 'web', 'permissions' => []],
        ];
    }

    private function permissions(): array
    {
       return[];
    }
}

```

After publishing ShieldSeeder add the following lines of code to the `DatabaseSeeder.php`
```php
$this->call([
    ShieldSeeder::class,
    UserSeeder::class,
    OrganizationSeeder::class
]);
```

After that fun the following commands

```bash
php artisan migrate:fresh --seed
php artisan shield:generate --all
```

Add these plugins To `app/Providers/Filament/AdminPanelProvider.php` plugin method.

```php
<?php

namespace App\Providers\Filament;

use IracodeCom\FilamentNotification\FilamentNotificationPlugin;
use Filament\PanelProvider;
use Filament\Panel;

class AdminPanelProvider extends PanelProvider
{
    public function panel( Panel $panel ) : Panel
    {
        return $panel
            ->plugins([
                FilamentShieldPlugin::make(),
                BreezyCore::make()->myProfile(hasAvatars: true),
                FilamentOrganizationalShieldPlugin::make(),
            ]);
    }
}
```

# Login info
```php
email:    admin@test.com
password: password
```

# Security

If you discover any security related issues, please email `ardavanshamroshan@yahoo.com` instead of using the issue tracker.

# License

The MIT License (MIT). Please see [License File](LICENSE) for more information.