<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrganizationResource\Pages\EditOrganization;
use App\Filament\Resources\OrganizationResource\Pages\ListOrganizations;
use App\Models\Organization;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms\Form;
use Filament\Resources\Resource;

class OrganizationResource extends Resource implements HasShieldPermissions
{
    use \App\Traits\HasShieldPermissions;

    protected static ?string $model = Organization::class;

    protected static ?string $navigationIcon           = 'heroicon-o-rectangle-stack';
    protected static bool    $shouldRegisterNavigation = false;

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
        return $form->schema(EditOrganization::schema());
    }

    public static function getPages(): array
    {
        return [
            'index' => ListOrganizations::route('/'),
            // 'create'    => Pages\CreateOrganization::route('/create'),
            'edit'  => EditOrganization::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
