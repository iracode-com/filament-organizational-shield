<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Services\PermissionService;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Arr;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make(),];
    }

    public function save(bool $shouldRedirect = true, bool $shouldSendSavedNotification = true): void
    {
        parent::save($shouldRedirect, $shouldSendSavedNotification);


        $permissionsState = $this->form->getState()['permissions'];
        Arr::forget($permissionsState, 'resources');
        $state            = Arr::flatten($permissionsState);
        app(PermissionService::class)->syncPermissions($this->getRecord(), $state);
    }
}
