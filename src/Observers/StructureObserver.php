<?php

namespace IracodeCom\FilamentOrganizationalShield\Observers;

use IracodeCom\FilamentOrganizationalShield\Models\Organization;
use IracodeCom\FilamentOrganizationalShield\Models\Structure;

class StructureObserver
{
    public function creating(Structure $structure): void
    {
        $structure->organization_id = Organization::query()->first()->id;
    }
}
