<?php

namespace IracodeCom\FilamentOrganizationalShield\Resources\PositionResource\Pages;

use IracodeCom\FilamentOrganizationalShield\Resources\PositionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPosition extends EditRecord
{
    protected static string $resource = PositionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
