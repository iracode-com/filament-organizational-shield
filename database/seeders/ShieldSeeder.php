<?php

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
                        // 'name'       => $permission['name'],
                        // 'guard_name' => $permission['guard_name'],
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
        // $permissions = [];
        // $resources = FilamentShield::getResources();
        //
        // foreach ($resources as $resource) {
        //     $model           = app(app($resource['fqcn'])->getModel());
        //     $name            = $resource['model'];
        //     $columns         = $model->getConnection()->getSchemaBuilder()->getColumns($model->getTable());
        //     $nullableColumns = Arr::pluck(
        //         Arr::where($columns, fn($value) => $value['nullable'] == true),
        //         'name'
        //     );
        //     $permissions[] = Arr::map($nullableColumns, fn($column) => str('access')->append('_')->append($column)->append($name)->snake()->value());
        // }
        //
        // return Arr::flatten($permissions);
        return[];
    }
}
