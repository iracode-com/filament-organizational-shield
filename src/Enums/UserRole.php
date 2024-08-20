<?php

namespace IracodeCom\FilamentOrganizationalShield\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasDescription;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum UserRole: string implements HasLabel, HasColor, HasIcon, HasDescription
{
    case ADMIN = 'admin';
    case USER  = 'user';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::ADMIN => __('Admin'),
            self::USER  => __('User'),
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::ADMIN => 'info',
            self::USER  => 'gray',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::ADMIN => 'heroicon-o-finger-print',
            self::USER  => 'heroicon-o-user',
        };
    }

    public function getDescription(): ?string
    {
        return match ($this) {
            self::ADMIN => __('User can login as admin'),
            self::USER  => __('User does not have access to admin panel'),
        };
    }
}
