<?php

namespace App\Observers;

use App\Models\Organization;

class OrganizationObserver
{
    /**
     * Handle the Organization "created" event.
     */
    public function created(Organization $organization): void
    {
        $organization->structures()->create(['name' => $organization->name]);
    }
}
