<?php

namespace IracodeCom\FilamentOrganizationalShield\Resources;

use Filament\Panel;
use IracodeCom\FilamentOrganizationalShield\Resources\OrganizationResource\Pages\CreateOrganization;
use IracodeCom\FilamentOrganizationalShield\Resources\OrganizationResource\Pages\EditOrganization;
use IracodeCom\FilamentOrganizationalShield\Resources\OrganizationResource\Pages\ListOrganizations;
use IracodeCom\FilamentOrganizationalShield\Models\Organization;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms\Form;
use Filament\Resources\Resource;

class OrganizationResource extends Resource implements HasShieldPermissions
{
    use \IracodeCom\FilamentOrganizationalShield\Traits\HasShieldPermissions;

    protected static ?string $model = Organization::class;

    protected static bool $shouldRegisterNavigation = false;

    public static function getNavigationGroup(): ?string
    {
        return __('Organizational information');
    }

    public static function getNavigationLabel(): string
    {
        return __('Organizational specification');
    }

    public static function getPluralLabel(): ?string
    {
        return __('Organizational specification');
    }

    public static function getLabel(): ?string
    {
        return __('Organizational specification');
    }

    public static function form(Form $form): Form
    {
        return $form->schema(OrganizationResource\Pages\EditOrganization::schema());
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListOrganizations::route('/'),
            'create' => CreateOrganization::route('/create'),
            'edit'   => EditOrganization::route('/{record}/edit'),
        ];
    }
}
