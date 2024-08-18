<?php

namespace App\Filament\Resources\Shield\RoleResource\Pages;

use App\Filament\Resources\Shield\RoleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables;
use Illuminate\Support\Str;

class ListRoles extends ListRecords
{
    protected static string $resource = RoleResource::class;

    public static function schema(): array
    {
        return [
            Tables\Columns\TextColumn::make('name')
                ->badge()
                ->label(__('filament-shield::filament-shield.column.name'))
                ->formatStateUsing(fn($state): string => is_string($state) ? Str::headline($state) : $state->getLabel())
                ->searchable(),
            Tables\Columns\TextColumn::make('guard_name')
                ->badge()
                ->label(__('filament-shield::filament-shield.column.guard_name')),
            Tables\Columns\TextColumn::make('permissions_count')
                ->badge()
                ->label(__('filament-shield::filament-shield.column.permissions'))
                ->counts('permissions')
                ->colors(['success']),
            Tables\Columns\TextColumn::make('updated_at')
                ->jalaliDateTime()
                ->label(__('filament-shield::filament-shield.column.updated_at')),
        ];
    }

    public static function actions(): array
    {
        return [
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ];
    }

    public static function bulkActions(): array
    {
        return [
            Tables\Actions\DeleteBulkAction::make(),
        ];
    }

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
