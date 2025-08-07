<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Tradicion;
use App\Models\Evento;
use App\Models\SitioCultural;
use App\Policies\TradicionPolicy;
use App\Policies\EventoPolicy;
use App\Policies\SitioCulturalPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Tradicion::class => TradicionPolicy::class,
        Evento::class => EventoPolicy::class,
        SitioCultural::class => SitioCulturalPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}