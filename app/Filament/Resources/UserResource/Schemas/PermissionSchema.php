<?php

namespace App\Filament\Resources\UserResource\Schemas;

use App\Filament\Resources\Shield\RoleResource;
use BezhanSalleh\FilamentShield\Facades\FilamentShield;
use BezhanSalleh\FilamentShield\Forms\ShieldSelectAllToggle;
use CodeWithDennis\SimpleAlert\Components\Forms\SimpleAlert;
use Filament\Forms;
use Illuminate\Support\Arr;
use Illuminate\Support\HtmlString;

class PermissionSchema
{
    public static function schema(): array
    {
        return [
            Forms\Components\Section::make(__('Authorizations'))->schema([
                SimpleAlert::make('permissions_help')
                    ->title(__('How to manage the level of access to records?'))
                    ->description(__('How to manage the level of access to records? You can determine what records the person or role you want is allowed to see based on access to records Record ownership settings which is determined on the module specification page, for example, you can determine that each sales expert Only see sales opportunities related to yourself and other members of your work team.'))
                    ->warning()
                    ->columnSpanFull(),

                ShieldSelectAllToggle::make('select_all')
                    ->columnSpanFull()
                    ->onIcon('heroicon-s-shield-check')
                    ->offIcon('heroicon-s-shield-exclamation')
                    ->label(__('filament-shield::filament-shield.field.select_all.name'))
                    ->helperText(fn(): HtmlString => new HtmlString(__('filament-shield::filament-shield.field.select_all.message')))
                    ->dehydrated(fn($state): bool => $state),
                Forms\Components\Tabs::make('permission_id')
                    ->contained(false)
                    ->tabs([
                        RoleResource::getTabFormComponentForResources(),
                        RoleResource::getTabFormComponentForPage(),
                        RoleResource::getTabFormComponentForWidget(),
                        RoleResource::getTabFormComponentForCustomPermissions(),
                    ])->columnSpan(2),
            ])
        ];
    }
}