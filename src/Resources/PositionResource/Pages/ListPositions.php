<?php

namespace IracodeCom\FilamentOrganizationalShield\Resources\PositionResource\Pages;

use IracodeCom\FilamentOrganizationalShield\Resources\PositionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\MaxWidth;

class ListPositions extends ListRecords
{
    protected static string $resource = PositionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->modalWidth(MaxWidth::Medium),
        ];
    }
}
