<?php

namespace IracodeCom\FilamentOrganizationalShield\Resources\UserResource\Schemas;

use IracodeCom\FilamentOrganizationalShield\Resources\RoleResource\Pages\CreateRole;
use Spatie\Permission\Models\Role;
use Filament\Forms;
use Illuminate\Database\Eloquent\Model;

class RoleSchema
{
    public static function schema(): array
    {
        return [
            Forms\Components\Section::make(__('Roles'))->schema([
                Forms\Components\Select::make('role_id')
                    ->label(__('Roles'))
                    ->relationship(name: 'roles', titleAttribute: 'name')
                    ->getOptionLabelFromRecordUsing(
                        fn(Model $record) => is_string($record->name) ?
                            $record->name :
                            $record->name->getLabel() ??
                            $record->name->value
                    )
                    ->inlineLabel()
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->reactive()
                    ->suffixAction(
                        Forms\Components\Actions\Action::make('create')
                            
                            ->icon('heroicon-o-plus')
                            ->color('gray')
                            ->url(CreateRole::getUrl())
                    )
                    ->hintActions([
                        Forms\Components\Actions\Action::make('select_all')
                            // ->icon('heroicon-o-clipboard-document-check')
                            // ->iconButton()
                            
                            ->hidden(fn(Forms\Components\Component $component) => count($component->getState()) == Role::count())
                            ->action(fn(Forms\Components\Component $component) => $component->state(Role::pluck('id')->toArray())),
                        Forms\Components\Actions\Action::make('delete_selected')
                            // ->icon('heroicon-o-x-circle')
                            // ->iconButton()
                            
                            ->hidden(fn(Forms\Components\Component $component) => empty($component->getState()))
                            ->action(fn(Forms\Components\Component $component) => $component->state([]))
                    ])

            ])
        ];
    }
}