<?php

namespace App\Filament\Resources\Shield\RoleResource\Pages;

use App\Filament\Resources\Shield\RoleResource;
use BezhanSalleh\FilamentShield\Forms\ShieldSelectAllToggle;
use BezhanSalleh\FilamentShield\Support\Utils;
use CodeWithDennis\SimpleAlert\Components\Forms\SimpleAlert;
use Filament\Forms;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;

class CreateRole extends CreateRecord
{
    protected static string $resource = RoleResource::class;

    public Collection $permissions;

    public static function schema(): array
    {
        return [
            Forms\Components\Grid::make()
                ->schema([
                    Forms\Components\Section::make()
                        ->schema([
                            SimpleAlert::make('role_name')
                                ->title(__('Enter the role name in English and use hyphen (-) to separate words.'))
                                ->description(__('for example: writer, super-admin, developer, security-man'))
                                ->warning()
                                ->columnSpanFull(),

                            Forms\Components\TextInput::make('name')
                                ->label(__('filament-shield::filament-shield.field.name'))
                                ->afterStateUpdated(fn($state) => str($state)->slug()->value())
                                ->unique(ignoreRecord: true)
                                ->required()
                                // ->disabledOn('edit')
                                ->maxLength(255),

                            Forms\Components\TextInput::make('guard_name')
                                ->label(__('filament-shield::filament-shield.field.guard_name'))
                                ->default(Utils::getFilamentAuthGuard())
                                // ->disabled()
                                ->nullable()
                                ->maxLength(255),

                            ShieldSelectAllToggle::make('select_all')
                                ->onIcon('heroicon-s-shield-check')
                                ->offIcon('heroicon-s-shield-exclamation')
                                ->label(__('filament-shield::filament-shield.field.select_all.name'))
                                ->helperText(fn(): HtmlString => new HtmlString(__('filament-shield::filament-shield.field.select_all.message')))
                                ->dehydrated(fn($state): bool => $state),

                        ])
                        ->columns([
                            'sm' => 2,
                            'lg' => 3,
                        ]),
                ]),
            Forms\Components\Tabs::make('Permissions')
                ->tabs([
                    static::$resource::getTabFormComponentForResources(),
                    static::$resource::getTabFormComponentForPage(),
                    static::$resource::getTabFormComponentForWidget(),
                    static::$resource::getTabFormComponentForCustomPermissions(),
                ])
                ->columnSpan('full'),
        ];
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->permissions = collect($data)
            ->filter(function ($permission, $key) {
                return ! in_array($key, ['name', 'guard_name', 'select_all']);
            })
            ->values()
            ->flatten()
            ->unique();

        return Arr::only($data, ['name', 'guard_name']);
    }

    protected function afterCreate(): void
    {
        $permissionModels = collect();
        $this->permissions->each(function ($permission) use ($permissionModels) {
            $permissionModels->push(Utils::getPermissionModel()::firstOrCreate([
                /** @phpstan-ignore-next-line */
                'name'       => $permission,
                'guard_name' => $this->data['guard_name'],
            ]));
        });

        $this->record->syncPermissions($permissionModels);
    }
}
