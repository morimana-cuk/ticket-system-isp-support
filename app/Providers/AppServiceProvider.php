<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\PelangganRepositoryInterface;
use App\Repositories\Contracts\PaketInternetRepositoryInterface;
use App\Repositories\Contracts\PegawaiRepositoryInterface;
use App\Repositories\Contracts\TicketRepositoryInterface;
use App\Repositories\Contracts\TicketStatusHistoryRepositoryInterface;
use App\Repositories\Eloquent\EloquentPelangganRepository;
use App\Repositories\Eloquent\EloquentPaketInternetRepository;
use App\Repositories\Eloquent\EloquentPegawaiRepository;
use App\Repositories\Eloquent\EloquentTicketRepository;
use App\Repositories\Eloquent\EloquentTicketStatusHistoryRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(PelangganRepositoryInterface::class, EloquentPelangganRepository::class);
        $this->app->bind(PaketInternetRepositoryInterface::class, EloquentPaketInternetRepository::class);
        $this->app->bind(PegawaiRepositoryInterface::class, EloquentPegawaiRepository::class);
        $this->app->bind(TicketRepositoryInterface::class, EloquentTicketRepository::class);
        $this->app->bind(TicketStatusHistoryRepositoryInterface::class, EloquentTicketStatusHistoryRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
