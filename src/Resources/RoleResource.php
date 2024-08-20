<?php

namespace IracodeCom\FilamentOrganizationalShield\Resources;

use IracodeCom\FilamentOrganizationalShield\Resources\RoleResource\Pages;
use IracodeCom\FilamentOrganizationalShield\Models\User;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use BezhanSalleh\FilamentShield\Facades\FilamentShield;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use BezhanSalleh\FilamentShield\Support\Utils;
use CodeWithDennis\SimpleAlert\Components\Forms\SimpleAlert;
use Filament\Forms;
use Filament\Forms\Components\Component;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;

class RoleResource extends Resource implements HasShieldPermissions
{
    protected static ?string $recordTitleAttribute = 'name';

    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'delete',
            'delete_any',
        ];
    }

    public static function getRecordTitle(?Model $record): string|Htmlable|null
    {
        return is_string($record->name) ?
            $record->name :
            $record->name->getLabel() ??
            $record->name->value;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema(Pages\CreateRole::schema());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(Pages\ListRoles::schema())
            ->actions(Pages\ListRoles::actions())
            ->bulkActions(Pages\ListRoles::bulkActions());
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'view'   => Pages\ViewRole::route('/{record}'),
            'edit'   => Pages\EditRole::route('/{record}/edit'),
        ];
    }

    public static function getCluster(): ?string
    {
        return Utils::getResourceCluster() ?? static::$cluster;
    }

    public static function getModel(): string
    {
        return Utils::getRoleModel();
    }

    public static function getModelLabel(): string
    {
        return __('filament-shield::filament-shield.resource.label.role');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament-shield::filament-shield.resource.label.roles');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return Utils::isResourceNavigationRegistered();
    }

    public static function getNavigationGroup(): ?string
    {
        return Utils::isResourceNavigationGroupEnabled()
            ? __('filament-shield::filament-shield.nav.group')
            : '';
    }

    public static function getNavigationLabel(): string
    {
        return __('filament-shield::filament-shield.nav.role.label');
    }

    public static function getNavigationIcon(): string
    {
        return __('filament-shield::filament-shield.nav.role.icon');
    }

    public static function getNavigationSort(): ?int
    {
        return Utils::getResourceNavigationSort();
    }

    public static function getSlug(): string
    {
        return Utils::getResourceSlug();
    }

    public static function getNavigationBadge(): ?string
    {
        return Utils::isResourceNavigationBadgeEnabled()
            ? strval(static::getEloquentQuery()->count())
            : null;
    }

    public static function isScopedToTenant(): bool
    {
        return Utils::isScopedToTenant();
    }

    public static function canGloballySearch(): bool
    {
        return Utils::isResourceGloballySearchable() && count(static::getGloballySearchableAttributes()) && static::canViewAny();
    }

    public static function getResourceEntitiesSchema(): ?array
    {
        return collect(FilamentShield::getResources())
            ->sortKeys()
            ->map(function ($entity) {
                $sectionLabel = strval(
                    static::shield()->hasLocalizedPermissionLabels()
                        ? FilamentShield::getLocalizedResourceLabel($entity['fqcn'])
                        : $entity['model']
                );

                return Forms\Components\Fieldset::make($sectionLabel)
                    // ->description(fn() => new HtmlString('<span style="word-break: break-word;">' . Utils::showModelPath($entity['fqcn']) . '</span>'))
                    ->visible(fn(Forms\Get $get) => // ! $get('resources')
                        // ||
                    in_array($entity['resource'], $get('resources')))
                    ->schema([
                        Forms\Components\Tabs::make(__('Permissions'))
                            ->contained(false)
                            ->tabs([
                                Forms\Components\Tabs\Tab::make(__('Module access'))->schema([static::getCheckBoxListComponentForResource($entity)]),
                                Forms\Components\Tabs\Tab::make(__('Record access'))->schema([static::getCheckBoxListComponentForResourceRecordAccess($entity)]),
                                Forms\Components\Tabs\Tab::make(__('Field access'))
                                    ->schema([static::getCheckBoxListComponentForResourceFieldAccess($entity)])
                                    ->visible(fn() => method_exists($entity['fqcn'], 'getFieldAccessPermissionPrefixes'))
                            ])->columnSpanFull()
                    ])
                    ->columnSpan(static::shield()->getSectionColumnSpan());
            })
            ->toArray();
    }

    public static function getResourceTabBadgeCount(): ?int
    {
        return collect(FilamentShield::getResources())
            ->map(fn($resource) => count(static::getResourcePermissionOptions($resource)))
            ->sum();
    }

    public static function getResourcePermissionOptions(array $entity): array
    {
        $reject = [];
        if (method_exists($entity['fqcn'], 'getFieldAccessPermissionPrefixes')) {
            $reject = app($entity['fqcn'])::getFieldAccessPermissionPrefixes();
        }

        $modulePermissions = array_diff(
            Utils::getResourcePermissionPrefixes($entity['fqcn']),
            [
                ...config('filament-organizational-shield.permission_prefixes.custom'),
                ...$reject
            ]
        );
        return collect($modulePermissions)
            ->flatMap(function ($permission) use ($entity) {
                $name  = $permission . '_' . $entity['resource'];
                $label = static::shield()->hasLocalizedPermissionLabels()
                    ? FilamentShield::getLocalizedResourcePermissionLabel($permission)
                    : $name;

                return [
                    $name => $label,
                ];
            })
            ->toArray();
    }

    public static function getResourceRecordAccessPermissionOptions(array $entity): array
    {
        $reject = [];
        if (method_exists($entity['fqcn'], 'getFieldAccessPermissionPrefixes')) {
            $reject = app($entity['fqcn'])::getFieldAccessPermissionPrefixes();
        }

        $record = array_diff(
            Utils::getResourcePermissionPrefixes($entity['fqcn']),
            [
                ...config('filament-organizational-shield.permission_prefixes.default'),
                ...$reject
            ]
        );
        return collect($record)
            ->flatMap(function ($permission) use ($entity) {
                $name  = $permission . '_' . $entity['resource'];
                $label = static::shield()->hasLocalizedPermissionLabels()
                    ? FilamentShield::getLocalizedResourcePermissionLabel($permission)
                    : $name;

                return [
                    $name => $label,
                ];
            })
            ->toArray();
    }

    public static function getResourceFieldAccessPermissionOptions(array $entity): array
    {
        $fieldPermissions = [];
        if (method_exists($entity['fqcn'], 'getFieldAccessPermissionPrefixes')) {
            $fieldPermissions = app($entity['fqcn'])::getFieldAccessPermissionPrefixes();
        }

        return collect($fieldPermissions)
            ->flatMap(function ($permission) use ($entity) {
                $name  = $permission . '_' . $entity['resource'];
                $label = static::shield()->hasLocalizedPermissionLabels()
                    ? FilamentShield::getLocalizedResourcePermissionLabel($permission)
                    : $name;

                return [
                    $name => $label,
                ];
            })
            ->toArray();
    }

    public static function setPermissionStateForRecordPermissions(Component $component, string $operation, array $permissions, ?Model $record): void
    {
        if (in_array($operation, ['edit', 'view'])) {

            if (blank($record)) {
                return;
            }
            if ($component->isVisible() && count($permissions) > 0) {
                $state = $component->getState();
                $component->state(
                    collect($permissions)
                        /** @phpstan-ignore-next-line */
                        ->filter(fn($value, $key) => $record->checkPermissionTo($key))
                        ->keys()
                        ->merge($state)
                        ->toArray()
                );
            }
        }
    }

    public static function getPageOptions(): array
    {
        return collect(FilamentShield::getPages())
            ->flatMap(fn($page) => [
                $page['permission'] => static::shield()->hasLocalizedPermissionLabels()
                    ? FilamentShield::getLocalizedPageLabel($page['class'])
                    : $page['permission'],
            ])
            ->toArray();
    }

    public static function getWidgetOptions(): array
    {
        return collect(FilamentShield::getWidgets())
            ->flatMap(fn($widget) => [
                $widget['permission'] => static::shield()->hasLocalizedPermissionLabels()
                    ? FilamentShield::getLocalizedWidgetLabel($widget['class'])
                    : $widget['permission'],
            ])
            ->toArray();
    }

    public static function getCustomPermissionOptions(): ?array
    {
        return FilamentShield::getCustomPermissions()
            ->mapWithKeys(fn($customPermission) => [
                $customPermission => static::shield()->hasLocalizedPermissionLabels()
                    ? FilamentShield::getLocalizedResourcePermissionLabel(
                        str($customPermission)->before('::')->value()
                    )
                    : $customPermission,
            ])
            ->toArray();
    }

    public static function getTabFormComponentForResources(): Component
    {
        return static::shield()->hasSimpleResourcePermissionView()
            ? static::getTabFormComponentForSimpleResourcePermissionsView()
            : Forms\Components\Tabs\Tab::make('resources')
                ->label(__('filament-shield::filament-shield.resources'))
                ->visible(fn(): bool => (bool) Utils::isResourceEntityEnabled())
                ->badge(static::getResourceTabBadgeCount())
                ->schema([
                    SimpleAlert::make('Select module')
                        ->info()
                        ->columnSpanFull()
                        ->title(__('Select module'))
                        ->description(__('To assign access levels to the user, first select the desired modules so that the corresponding access list is displayed.')),

                    Forms\Components\Select::make('resources')
                        ->hint(new HtmlString(Blade::render('<x-filament::loading-indicator wire:loading wire:target="data.resources" class="h-5 w-5"/>')))
                        ->label(__('Module'))
                        ->options(function () {
                            return Arr::mapWithKeys(FilamentShield::getResources(), function (array $items, string $key) {
                                return [$key => __(str($items['resource'])->headline()->plural()->value())];
                            });
                        })
                        ->searchable()
                        ->preload()
                        ->multiple()
                        ->reactive(),

                    SimpleAlert::make('Super admin user')
                        ->warning()
                        ->visible(fn(?Model $record) => $record instanceof User)
                        ->title(__('Attention'))
                        ->description(__('Consider user role when choosing access. User accesses with super admin role cannot be changed.')),

                    Forms\Components\Grid::make()
                        ->schema(static::getResourceEntitiesSchema())
                        ->columns(static::shield()->getGridColumns()),
                ])->columns();
    }

    // Module access permissions (default shield accesses from policy)
    public static function getCheckBoxListComponentForResource(array $entity): Component
    {
        $permissionsArray = static::getResourcePermissionOptions($entity);
        return static::getCheckboxListFormComponent($entity['resource'], $permissionsArray, false);
    }

    // Record access permissions
    public static function getCheckBoxListComponentForResourceRecordAccess(array $entity): Component
    {
        $permissionsArray = static::getResourceRecordAccessPermissionOptions($entity);
        return static::getCheckboxListFormComponent($entity['resource'], $permissionsArray, false);
    }

    //  Field access permissions
    public static function getCheckBoxListComponentForResourceFieldAccess(array $entity): Component
    {
        $permissionsArray = static::getResourceFieldAccessPermissionOptions($entity);
        return static::getCheckboxListFormComponent($entity['resource'], $permissionsArray, false);
    }


    public static function getTabFormComponentForPage(): Component
    {
        $options = static::getPageOptions();
        $count   = count($options);

        return Forms\Components\Tabs\Tab::make('pages')
            ->label(__('filament-shield::filament-shield.pages'))
            ->visible(fn(): bool => (bool) Utils::isPageEntityEnabled() && $count > 0)
            ->badge($count)
            ->schema([
                static::getCheckboxListFormComponent('pages_tab', $options),
            ]);
    }

    public static function getTabFormComponentForWidget(): Component
    {
        $options = static::getWidgetOptions();
        $count   = count($options);

        return Forms\Components\Tabs\Tab::make('widgets')
            ->label(__('filament-shield::filament-shield.widgets'))
            ->visible(fn(): bool => (bool) Utils::isWidgetEntityEnabled() && $count > 0)
            ->badge($count)
            ->schema([
                static::getCheckboxListFormComponent('widgets_tab', $options),
            ]);
    }

    public static function getTabFormComponentForCustomPermissions(): Component
    {
        $options = static::getCustomPermissionOptions();
        $count   = count($options);

        return Forms\Components\Tabs\Tab::make('custom')
            ->label(__('filament-shield::filament-shield.custom'))
            ->visible(fn(): bool => (bool) Utils::isCustomPermissionEntityEnabled() && $count > 0)
            ->badge($count)
            ->schema([
                static::getCheckboxListFormComponent('custom_permissions', $options),
            ]);
    }

    public static function getTabFormComponentForSimpleResourcePermissionsView(): Component
    {
        $options = FilamentShield::getAllResourcePermissions();
        $count   = count($options);

        return Forms\Components\Tabs\Tab::make('resources')
            ->label(__('filament-shield::filament-shield.resources'))
            ->visible(fn(): bool => (bool) Utils::isResourceEntityEnabled() && $count > 0)
            ->badge($count)
            ->schema([
                static::getCheckboxListFormComponent('resources_tab', $options),
            ]);
    }

    public static function getCheckboxListFormComponent(string $name, array $options, bool $searchable = true): Component
    {
        return Forms\Components\CheckboxList::make($name)
            ->label('')
            ->options(fn(): array => $options)
            ->disabled(fn(?Model $record) => $record instanceof User ? $record->hasRole(['super_admin']) : false)
            ->searchable($searchable)
            ->afterStateHydrated(
                fn(Component $component, string $operation, ?Model $record) => static::setPermissionStateForRecordPermissions(
                    component  : $component,
                    operation  : $operation,
                    permissions: $options,
                    record     : $record
                )
            )
            ->dehydrated(fn($state) => ! blank($state))
            ->bulkToggleable()
            ->gridDirection('row')
            ->columns(static::shield()->getCheckboxListColumns())
            ->columnSpan(static::shield()->getCheckboxListColumnSpan());
    }

    public static function shield(): FilamentShieldPlugin
    {
        return FilamentShieldPlugin::get();
    }
}
