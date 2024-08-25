<?php

namespace IracodeCom\FilamentOrganizationalShield\Resources\OrganizationResource\Widgets;

use IracodeCom\FilamentOrganizationalShield\Models\Structure;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\TextInput;
use Filament\Support\Contracts\HasDescription;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\On;
use SolutionForest\FilamentTree\Actions\DeleteAction;
use SolutionForest\FilamentTree\Actions\EditAction;
use SolutionForest\FilamentTree\Actions\ViewAction;
use SolutionForest\FilamentTree\Widgets\Tree as BaseWidget;

class StructureWidget extends BaseWidget implements HasDescription, HasActions
{
    use InteractsWithActions;

    protected static string $model           = Structure::class;
    protected static int    $maxDepth        = 10;
    protected bool          $enableTreeTitle = true;

    public function getTreeTitle(): ?string
    {
        return __('Organizational structure');
    }

    public function getDescription(): ?string
    {
        return __('To create a new structure, right-click and select Create Structure.');
    }

    #[On('structure-updated')]
    public function updateTree(?array $list = null): array
    {
        return parent::updateTree($list);
    }

    protected function getFormSchema(): array
    {
        return [
            TextInput::make('title')->required(),
        ];
    }

    public function getTreeRecordIcon(?Model $record = null): ?string
    {
        return 'heroicon-o-building-office';
    }

    protected function getTreeActions(): array
    {
        return [
            ViewAction::make()->modalWidth(MaxWidth::Medium),
            EditAction::make()->modalWidth(MaxWidth::Medium),
            DeleteAction::make(),
        ];
    }
}