<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Schema::defaultStringLength(191);
        \Voyager::addAction(\App\Actions\PresentarAction::class);
        \Voyager::addAction(\App\Actions\HabilitarAction::class);
        \Voyager::addAction(\App\Actions\AprobarAction::class);
        \Voyager::addAction(\App\Actions\DesaprobarAction::class);
        \Voyager::addAction(\App\Actions\SeleccionarAction::class);
        \Voyager::addAction(\App\Actions\DeseleccionarAction::class);
        \Voyager::addAction(\App\Actions\PresentarEvaluacionAction::class);
        \Voyager::addAction(\App\Actions\VistaRapidaAction::class);
        \Voyager::addAction(\App\Actions\VerResumenAction::class);
        \Voyager::addAction(\App\Actions\VerPostulacionAction::class);
    }
}
