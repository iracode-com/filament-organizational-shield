<?php

namespace App\Traits;

use AymanAlhattami\FilamentContextMenu\Traits\PageHasContextMenu;
use AymanAlhattami\FilamentContextMenu\Actions;

trait HasContextMenu
{
    use PageHasContextMenu;

    public function getContextMenuActions(): array
    {
        return [
            Actions\RefreshAction::make(),
            Actions\GoBackAction::make(),
            Actions\GoForwardAction::make(),
        ];
    }
}