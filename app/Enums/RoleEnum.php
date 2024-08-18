<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum RoleEnum: string implements HasLabel, HasColor
{
    case SUPER_ADMIN = 'super_admin';
    case PANEL_USER  = 'panel_user';
    case ADMIN       = 'admin';
    case USER        = 'user';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::ADMIN       => __('Admin'),
            self::USER        => __('User'),
            self::SUPER_ADMIN => __('Super Admin'),
            self::PANEL_USER  => __('Panel User'),
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::ADMIN, self::SUPER_ADMIN => 'info',
            self::USER                     => 'gray',
            self::PANEL_USER               => 'warning',
        };
    }
}
