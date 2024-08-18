<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Enums\UserRole;
use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables;
use Spatie\Permission\Models\Role;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public static function schema(): array
    {
        return [
            Tables\Columns\TextColumn::make('name')->searchable(),
            Tables\Columns\TextColumn::make('email')->searchable(),
            Tables\Columns\TextColumn::make('national_code')->searchable(),
            Tables\Columns\TextColumn::make('role')->sortable()->badge(),
            Tables\Columns\TextColumn::make('roles.name')->listWithLineBreaks()->limitList()->expandableLimitedList()->sortable()->badge()->color('warning')->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('roles.permissions.name')->listWithLineBreaks()->limitList()->expandableLimitedList()->sortable()->badge()->color('warning')->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('email_verified_at')->jalaliDateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('ip')->searchable()->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('agent')->searchable()->words(3)->tooltip(fn(User $user) => $user->agent)->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('last_login')->jalaliDateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\CheckboxColumn::make('status')->disabled(fn($record) => $record->id == auth()->id()),
            Tables\Columns\TextColumn::make('created_at')->jalaliDateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('updated_at')->jalaliDateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
        ];
    }
}
