<?php

namespace App\Providers;

use App\Models\Organization\Organization;
use Filament\Actions\MountableAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\Column;
use Filament\Tables\Filters\BaseFilter;
use Filament\Tables\Table;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->autoTranslateLabels();
    }


    private function autoTranslateLabels(): void
    {
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
}
