<?php

namespace App\Providers;

use App\Adapters\LeagueCSVReader;
use App\Contracts\AuthServiceInterface;
use App\Contracts\CSVAdapterInterface;
use App\Contracts\CSVServiceInterface;
use App\Contracts\OrderQueryInterface;
use App\Contracts\PatientQueryInterface;
use App\Queries\EloquentOrderQuery;
use App\Queries\EloquentPatientQuery;
use App\Services\CSVService;
use App\Services\JWTAuthService;
use Illuminate\Support\ServiceProvider;

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
        $this->app->bind(CSVAdapterInterface::class, LeagueCSVReader::class);
        $this->app->bind(CSVServiceInterface::class, CSVService::class);
        $this->app->bind(AuthServiceInterface::class, JWTAuthService::class);
        $this->app->bind(PatientQueryInterface::class, EloquentPatientQuery::class);
        $this->app->bind(OrderQueryInterface::class, EloquentOrderQuery::class);
    }
}
