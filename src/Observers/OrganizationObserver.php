<?php

namespace IracodeCom\FilamentOrganizationalShield\Observers;

use IracodeCom\FilamentOrganizationalShield\Models\Organization;

class OrganizationObserver
{
    /**
     * Handle the Organization "created" event.
     */
    public function created(Organization $organization): void
    {
        $organization->structures()->create(['title' => $organization->name]);
    }
}
