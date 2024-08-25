<?php

namespace IracodeCom\FilamentOrganizationalShield\Observers;

use App\Models\User;

class UserObserver
{
    public function created(User $user): void
    {
        $user->profile()->create();
        $user->syncRoles('user');
    }

    public function creating(User $user): void
    {
        $user->ip    = request()->ip();
        $user->agent = request()->userAgent();
    }

    public function updating(User $user): void
    {
        if ($user->ip != request()->ip()) {
            $user->ip = request()->ip();
        }

        if ($user->agent != request()->userAgent()) {
            $user->agent = request()->userAgent();
        }
    }
}
