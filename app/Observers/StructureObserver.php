<?php

namespace App\Observers;

use App\Models\Organization;
use App\Models\Structure;

class StructureObserver
{
    public function creating(Structure $structure): void
    {
        $structure->organization_id = Organization::query()->first()->id;
    }
}
