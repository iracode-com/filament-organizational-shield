<?php

namespace IracodeCom\FilamentOrganizationalShield\Resources\OrganizationResource\Pages;

use IracodeCom\FilamentOrganizationalShield\Resources\OrganizationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOrganizations extends ListRecords
{
    protected static string $resource = OrganizationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
