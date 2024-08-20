<?php

namespace IracodeCom\FilamentOrganizationalShield\Resources;

use IracodeCom\FilamentOrganizationalShield\Enums\UserRole;
use IracodeCom\FilamentOrganizationalShield\Resources\UserResource\Pages;
use IracodeCom\FilamentOrganizationalShield\Models\User;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class UserResource extends Resource implements HasShieldPermissions
{
    use \IracodeCom\FilamentOrganizationalShield\Traits\HasShieldPermissions;

    protected static ?string $model = User::class;

    protected static ?string $navigationIcon                  = 'heroicon-o-users';
    protected static ?string $tenantOwnershipRelationshipName = 'organizations';
    public ?array            $permissions                     = [];

    public static function getNavigationGroup(): ?string
    {
        return __('Users management');
    }

    public static function getLabel(): ?string
    {
        return __('User');
    }

    public static function getPluralLabel(): ?string
    {
        return __('Users');
    }

    public static function form(Form $form): Form
    {
        return $form->schema(Pages\CreateUser::schema());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(Pages\ListUsers::schema())
            ->filters([Tables\Filters\SelectFilter::make('role')->options(UserRole::class)])
            ->actions([Tables\Actions\EditAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit'   => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
