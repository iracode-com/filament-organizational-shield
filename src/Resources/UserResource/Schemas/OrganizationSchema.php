<?php

namespace IracodeCom\FilamentOrganizationalShield\Resources\UserResource\Schemas;

use IracodeCom\FilamentOrganizationalShield\Resources\OrganizationResource\Pages\EditOrganization;
use IracodeCom\FilamentOrganizationalShield\Resources\PositionResource;
use IracodeCom\FilamentOrganizationalShield\Models\Structure;
use CodeWithDennis\FilamentSelectTree\SelectTree;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Support\Enums\MaxWidth;

class OrganizationSchema
{
    public static function schema(): array
    {
        return [
            Forms\Components\Section::make(__('Organizational specification'))->schema([
                Forms\Components\Grid::make('organizational_information')
                    ->relationship('organizationalInformation', 'user_id')
                    ->schema([
                        SelectTree::make('structure_id')
                            ->label(__('Organizational structure'))
                            ->reactive()
                            ->inlineLabel()
                            ->relationship('structure', 'name', 'parent_id')
                            ->parentNullValue(-1)
                            ->withCount()
                            ->searchable()
                            ->enableBranchNode()
                            ->placeholder(__('Search'))
                            ->emptyLabel(__('No results found'))
                            ->suffixAction(
                                Forms\Components\Actions\Action::make('edit')
                                    ->color('gray')
                                    ->icon('heroicon-m-pencil-square')
                                    ->visible(fn(Forms\Get $get) => $get('structure_id'))
                                    ->form(EditOrganization::structureFormSchema())
                                    ->fillForm(function (Forms\Get $get) {
                                        $structure = Structure::query()->findOrFail($get('structure_id'));
                                        return [
                                            'name'      => $structure->name,
                                            'parent_id' => $structure->parent_id,
                                        ];
                                    })
                                    ->modalWidth(MaxWidth::Medium)
                                    ->action(fn(Forms\Get $get, $data) => Structure::query()->findOrFail($get('structure_id'))->update($data))
                            )
                            ->createOptionForm(EditOrganization::structureFormSchema())->columnSpanFull(),

                        Select::make('position_id')
                            ->label(__('Position'))
                            ->inlineLabel()
                            ->relationship('position', 'name')
                            ->searchable()
                            ->createOptionForm(PositionResource::schema())
                            ->editOptionForm(PositionResource::schema())
                            ->createOptionAction(fn(Forms\Components\Actions\Action $action) => $action->modalWidth(MaxWidth::Medium))
                            ->editOptionAction(fn(Forms\Components\Actions\Action $action) => $action->modalWidth(MaxWidth::Medium))
                            ->searchable()
                            ->preload()
                            ->columnSpanFull()
                    ]),
            ])
        ];
    }
}