<?php

namespace IracodeCom\FilamentOrganizationalShield\Traits;

use Illuminate\Support\Arr;

trait HasShieldPermissions
{
    public static function getPermissionPrefixes(): array
    {
        $permissions     = [];
        $model           = app(self::$model);
        $columns         = $model->getConnection()->getSchemaBuilder()->getColumns($model->getTable());
        $nullableColumns = Arr::pluck(
            Arr::where($columns, fn($value) => $value['nullable'] == true),
            'name'
        );
        $permissions[]   = Arr::map($nullableColumns, fn($column) => str('access')->append('_')->append($column)->value());

        return [
            ...config('filament-organizational-shield.permission_prefixes.resource'),
            ...Arr::flatten($permissions)
        ];
    }

    public static function getFieldAccessPermissionPrefixes(): array
    {
        $permissions     = [];
        $model           = app(self::$model);
        $columns         = $model->getConnection()->getSchemaBuilder()->getColumns($model->getTable());
        $nullableColumns = Arr::pluck(
            Arr::where($columns, fn($value) => $value['nullable'] == true),
            'name'
        );
        $permissions[]   = Arr::map($nullableColumns, fn($column) => str('access')->append('_')->append($column)->value());

        return [
            ...Arr::flatten($permissions)
        ];
    }
}