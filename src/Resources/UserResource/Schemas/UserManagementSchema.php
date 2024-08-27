<?php

namespace IracodeCom\FilamentOrganizationalShield\Resources\UserResource\Schemas;

use IracodeCom\FilamentOrganizationalShield\Enums\UserRole;
use App\Models\User;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Filament\Forms;
use Jeffgreco13\FilamentBreezy\Pages\MyProfilePage;

class UserManagementSchema
{
    public static function schema(): array
    {
        return [
            Forms\Components\Section::make(__('Personal information'))
                ->label(__('Personal information'))
                ->relationship('profile')
                ->schema(self::profileSchema())
                ->collapsible(),

            Forms\Components\Section::make(__('Login information'))->schema([
                Forms\Components\Split::make([
                    Forms\Components\Group::make([
                        Forms\Components\FileUpload::make('avatar_url')->inlineLabel()->avatar()->image()->imageEditor(),
                        Forms\Components\TextInput::make('name')->inlineLabel()->required(),
                        Forms\Components\TextInput::make('national_code')->required()->inlineLabel()->numeric(),
                        Forms\Components\TextInput::make('email')->inlineLabel()->nullable()->email()->unique('users', 'email', ignoreRecord: true),
                        Forms\Components\Placeholder::make('password')->inlineLabel()->content(new HtmlString('<a href="' . MyProfilePage::getUrl() . '" class="text-primary-600 dark:text-primary-400">تغییر کلمه عبور</a>'))
                            ->visible(fn(string $operation, ?User $record) => ($operation == 'edit')),
                        Forms\Components\TextInput::make('password')
                            ->inlineLabel()
                            ->live()
                            ->password()
                            ->revealable()
                            ->confirmed()
                            ->visible(fn(string $operation, ?User $record) => ($operation == 'create')),
                        Forms\Components\TextInput::make('password_confirmation')
                            ->inlineLabel()
                            ->live()
                            ->password()
                            ->revealable()
                            ->visible(fn(string $operation, ?User $record) => ($operation == 'create')),
                        Forms\Components\Radio::make('role')->options(UserRole::class)->inlineLabel()->default(UserRole::USER),
                    ]),
                    Forms\Components\Group::make([
                        Forms\Components\Placeholder::make('ip')->inlineLabel()->content(fn(?User $record) => $record?->ip ?? request()->ip()),
                        Forms\Components\Placeholder::make('agent')->inlineLabel()->content(fn(?User $record) => $record?->agent ?? request()->userAgent()),
                        Forms\Components\Placeholder::make('last_login')->inlineLabel()->content(fn(?User $record) => verta($record?->last_login)),
                        Forms\Components\Checkbox::make('must_password_reset')->reactive()->afterStateUpdated(function (Forms\Set $set) {
                            $set('can_password_reset', false);
                            $set('password_never_expires', false);
                        })->hint(new HtmlString(Blade::render('<x-filament::loading-indicator wire:loading wire:target="data.must_password_reset, data.can_password_reset, data.can_password_never_expires" class="h-5 w-5"/>'))),
                        Forms\Components\Checkbox::make('can_password_reset')->reactive()->afterStateUpdated(fn(Forms\Set $set, $state) => $set('must_password_reset', false)),
                        Forms\Components\Checkbox::make('password_never_expires')->reactive()
                    ])
                ])
            ])->collapsible(),
        ];
    }

    private static function profileSchema(): array
    {
        return [
            Forms\Components\Group::make([
                Forms\Components\TextInput::make('fullname')->inlineLabel(),
                Forms\Components\TextInput::make('mobile')->inlineLabel()->numeric(),
                Forms\Components\TextInput::make('tel')->inlineLabel()->numeric(),
                Forms\Components\TextInput::make('internal_tel')->inlineLabel()->numeric(),
                Forms\Components\TextInput::make('personnel_code')->inlineLabel()->numeric(),
            ])->columns()->columnSpanFull(),

            Forms\Components\Group::make([
                Forms\Components\Textarea::make('address')->inlineLabel()->rows(5),
                Forms\Components\Group::make([
                    Forms\Components\Checkbox::make('receive_email'),
                    Forms\Components\Checkbox::make('receive_sms'),
                    Forms\Components\Checkbox::make('receive_messenger')
                ]),
            ])->columns()
        ];
    }
}