<?php

namespace IracodeCom\FilamentOrganizationalShield\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class PermissionService
{
    public function syncPermissions(Model|User $user, $permissions, $rejectKey = null): void
    {

        $permissions = collect($permissions)
            ->reject(fn($value, $key) => $key == 'resources')
            ->values()
            ->flatten()
            ->unique()
            ->filter()
            ->toArray();

        $user->syncPermissions($permissions);
    }
}
