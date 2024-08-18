<?php

namespace App\Filament\Resources;

use App\Enums\UserRole;
use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class UserResource extends Resource implements HasShieldPermissions
{
    use \App\Traits\HasShieldPermissions;

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
        // return match ($form->getOperation()) {
        //     'create' => $form->schema(Pages\CreateUser::schema()),
        //     'edit'   => $form->schema(Pages\EditUser::schema()),
        //     default  => throw new \Exception('Unsupported'),
        // };

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

    public static function getRelations(): array
    {
        return [
            // PermissionsRelationManager::class
        ];
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
