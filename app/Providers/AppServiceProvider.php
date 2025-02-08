<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
//import FilamentView
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Blade;
use Filament\Forms\Components\DateTimePicker;

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
         
        // FilamentView::registerRenderHook(
        //     PanelsRenderHook::AUTH_LOGIN_FORM_BEFORE,
        //     fn (): string => Blade::render('filament.login_extra'),
        // );

        DateTimePicker::configureUsing(fn (DateTimePicker $component) => $component->format("d/m/Y"));
        DateTimePicker::configureUsing(fn (DateTimePicker $component) => $component->native(false));

    }
}
