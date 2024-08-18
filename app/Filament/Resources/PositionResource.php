<?php

namespace App\Filament\Resources;

use App\Models\Position;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Table;

class PositionResource extends Resource implements HasShieldPermissions
{
    use \App\Traits\HasShieldPermissions;

    protected static ?string $model = Position::class;

    public static function getNavigationGroup(): ?string
    {
        return __('Organizational information');
    }

    public static function getNavigationLabel(): string
    {
        return __('Positions');
    }

    public static function getLabel(): string
    {
        return __('Position');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Positions');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required(),
                Forms\Components\Textarea::make('description'),
                Forms\Components\Toggle::make('status')
                    ->required()
                    ->default(1),
            ])->columns(1);
    }    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\CheckboxColumn::make('status'),
                Tables\Columns\TextColumn::make('description'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->modalWidth(MaxWidth::Medium),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => PositionResource\Pages\ListPositions::route('/'),
            // 'create' => Pages\CreatePosition::route('/create'),
            // 'edit' => Pages\EditPosition::route('/{record}/edit'),
        ];
    }    public static function schema(): array
    {
        return [
            Forms\Components\TextInput::make('name'),
            Forms\Components\Textarea::make('description'),
        ];
    }
}
