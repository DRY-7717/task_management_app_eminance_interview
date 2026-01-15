<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Severity;
use App\Models\Status;
use App\Policies\SeverityPolicy;
use App\Policies\StatusPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
        Status::class => StatusPolicy::class,
        Severity::class => SeverityPolicy::class

    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
