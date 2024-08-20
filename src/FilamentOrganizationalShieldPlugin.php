<?php

namespace IracodeCom\FilamentOrganizationalShield;

use BezhanSalleh\FilamentShield\Support\Utils;
use Filament\Actions\MountableAction;
use Filament\Contracts\Plugin;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Navigation\NavigationItem;
use Filament\Panel;
use Filament\Tables\Columns\Column;
use Filament\Tables\Filters\BaseFilter;
use Filament\Tables\Table;
use IracodeCom\FilamentOrganizationalShield\Models\Organization;
use IracodeCom\FilamentOrganizationalShield\Resources\OrganizationResource\Pages\CreateOrganization;
use IracodeCom\FilamentOrganizationalShield\Resources\OrganizationResource\Pages\EditOrganization;

class FilamentOrganizationalShieldPlugin implements Plugin
{
    public static function make(): static
    {
        return app(static::class);
    }

    public function getId(): string
    {
        return 'filament-organizational-shield';
    }

    public function register(Panel $panel): void
    {
        if (! Utils::isResourcePublished()) {
            $panel->resources([
                Resources\RoleResource::class,
            ]);
        }
    }

    public function boot(Panel $panel): void
    {
        // if (! $panel->hasPlugin('filament-shield')) {
        //     $panel->plugin(FilamentShieldPlugin::make());
        // }
        //
        // if (! $panel->hasPlugin('filament-breezy')) {
        //     $panel->plugin(BreezyCore::make()->myProfile(hasAvatars: true));
        // }

        $panel->navigationItems([
            NavigationItem::make('organization')
                ->label(__('Organizational specification'))
                ->url(function (): string {

                    if (Utils::isResourcePublished()) {
                        $namespace              = 'App\\Filament\\Resources\\Shield\\OrganizationResource';
                        $editOrganizationPage   = $namespace . '\\Pages\EditOrganization';
                        $createOrganizationPage = $namespace . '\\Pages\CreateOrganization';
                    } else {
                        $editOrganizationPage   = EditOrganization::class;
                        $createOrganizationPage = CreateOrganization::class;
                    }

                    if ($id = Organization::query()->first()?->id) {
                        // return $editOrganizationPage::getUrl(['record' => $id]);
                        return route('filament.admin.resources.shield.organizations.edit', ['record' => $id]);

                    }

                    // return $createOrganizationPage::getUrl();
                    return route('filament.admin.resources.shield.organizations.create');
                })
                // ->visible(fn() => auth()->user()->can('update_organization::organization'))
                ->group(__('Organizational information')),
        ]);

        foreach ([Field::class, BaseFilter::class, Placeholder::class, Column::class, MountableAction::class] as $component) {
            $component::configureUsing(fn($c) => $c->translateLabel());
        }

        foreach ([DateTimePicker::class, DatePicker::class] as $component) {
            $component::configureUsing(fn($c) => $c->jalali()->prefixIcon('heroicon-o-calendar')->default(now()));
        }

        Column::configureUsing(fn($c) => $c->placeholder(__('No Data')));
        Table::configureUsing(fn($component) => $component->striped());
        Section::configureUsing(fn($component) => $component->compact());
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }
}