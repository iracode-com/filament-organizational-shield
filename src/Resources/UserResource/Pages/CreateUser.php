<?php

namespace IracodeCom\FilamentOrganizationalShield\Resources\UserResource\Pages;

use IracodeCom\FilamentOrganizationalShield\Resources\UserResource;
use IracodeCom\FilamentOrganizationalShield\Resources\UserResource\Schemas;
use IracodeCom\FilamentOrganizationalShield\Services\PermissionService;
use Filament\Forms;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource    = UserResource::class;
    public ?array           $permissions = [];

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }

    public static function schema(): array
    {
        return [
            Forms\Components\Tabs::make(__('User Management'))
                ->contained(false)
                ->tabs([
                    Forms\Components\Tabs\Tab::make(__('Expert personal information'))->icon('heroicon-o-user')
                        ->schema(Schemas\UserManagementSchema::schema()),

                    Forms\Components\Tabs\Tab::make(__('Roles'))->icon('heroicon-o-finger-print')
                        ->schema(Schemas\RoleSchema::schema()),

                    Forms\Components\Tabs\Tab::make(__('Organizational specification'))->icon('heroicon-o-building-office')
                        ->schema(Schemas\OrganizationSchema::schema()),

                    Forms\Components\Tabs\Tab::make(__('Authorizations'))->icon('heroicon-o-key')
                        ->schema(Schemas\PermissionSchema::schema())
                        ->statePath('permissions')
                ])->columnSpanFull()
                ->persistTabInQueryString()
        ];
    }

    public function create(bool $another = false): void
    {
        parent::create($another);

        app(PermissionService::class)
            ->syncPermissions($this->getRecord(), $this->form->getState()['permissions']);
    }
}
