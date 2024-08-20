<?php

namespace IracodeCom\FilamentOrganizationalShield\Resources\OrganizationResource\Pages;

use IracodeCom\FilamentOrganizationalShield\Resources\OrganizationResource;
use IracodeCom\FilamentOrganizationalShield\Resources\OrganizationResource\Widgets\StructureWidget;
use IracodeCom\FilamentOrganizationalShield\Models\Structure;
use CodeWithDennis\SimpleAlert\Components\Forms\SimpleAlert;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditOrganization extends EditRecord
{
    protected static string $resource                 = OrganizationResource::class;
    protected static bool   $shouldRegisterNavigation = true;

    public static function schema(): array
    {
        return [
            SimpleAlert::make('organizations')
                ->title(__('Create accounts for all companies and organizations you are associated with'))
                ->description(__('In its subcategories, first enter the name and logo of the organization and then complete information such as phone, fax, related industry, number of personnel and address. You can also add different subcategories of the organization by clicking the "Save" button and enter the information about each subcategory. This section will help you to have a comprehensive and up-to-date database of all organizations and subcategories related to your business. This information will be used in menus that require access levels up to the record level, organizational level and its subcategories.'))
                ->info()
                ->columnSpanFull(),

            Forms\Components\Tabs::make(__('Organization Details'))->tabs([
                Forms\Components\Tabs\Tab::make(__('Organizational information'))->schema([
                    FileUpload::make('logo')->inlineLabel()->avatar()->imageEditor(),
                    FileUpload::make('icon')->inlineLabel()->avatar()->imageEditor(),
                    Forms\Components\TextInput::make('name')->inlineLabel()->required(),
                    Forms\Components\TextInput::make('slug')->inlineLabel()->required()->disabled(fn(?string $operation, ?Model $record) => $operation == 'edit'),
                    Forms\Components\TextInput::make('tel')->inlineLabel()->tel()->numeric(),
                    Forms\Components\TextInput::make('fax')->inlineLabel()->numeric(),
                    Forms\Components\Select::make('industry')->options([
                        'agriculture'          => __('Agriculture'),
                        'clothing'             => __('Clothing'),
                        'banking'              => __('Banking'),
                        'electronic'           => __('Electronic'),
                        'engineering'          => __('Engineering'),
                        'governmental_centers' => __('Governmental centers'),
                    ])->inlineLabel()->searchable()->preload(),
                    Forms\Components\TextInput::make('personnel_count')->inlineLabel()->numeric()->default(0),
                    Forms\Components\Textarea::class::make('address')->inlineLabel()->columnSpanFull(),
                ])->columns(),
                Forms\Components\Tabs\Tab::make(__('Sales information'))->schema([
                    Forms\Components\TextInput::make('national_id')->numeric()->default(0),
                    Forms\Components\TextInput::make('economy_code')->numeric()->default(0),
                ])->columns()
            ])->columnSpanFull(),

        ];
    }

    public static function structureFormSchema(): array
    {
        return [
            Select::make('parent_id')->label(__('Parent'))
                ->inlineLabel()
                ->options(Structure::all()->pluck('title', 'id'))
                ->disableOptionWhen(fn(Forms\Get $get, string $value) => Structure::query()->findOrFail($value)->parent_id == $get('parent_id')),
            TextInput::make('name')->inlineLabel()->required(),
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            StructureWidget::class
        ];
    }
}
