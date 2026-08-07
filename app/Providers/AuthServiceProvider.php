<?php

namespace App\Providers;

use App\Models\VehicleRequest;
use App\Models\TravelRequest;
use App\Models\Vehicle;
use App\Models\Driver;
use App\Models\User;
use App\Policies\VehicleRequestPolicy;
use App\Policies\TravelRequestPolicy;
use App\Policies\VehiclePolicy;
use App\Policies\DriverPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        VehicleRequest::class => VehicleRequestPolicy::class,
        TravelRequest::class => TravelRequestPolicy::class,
        Vehicle::class => VehiclePolicy::class,
        Driver::class => DriverPolicy::class,
        User::class => UserPolicy::class,
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Additional gates for specific permissions
        Gate::define('view-dashboard', function (User $user) {
            return $user->hasAnyRole(['Admin', 'Staff', 'Super Admin']);
        });

        Gate::define('access-admin-panel', function (User $user) {
            return $user->hasAnyRole(['Admin', 'Super Admin']);
        });

        Gate::define('access-staff-panel', function (User $user) {
            return $user->hasAnyRole(['Staff', 'Motor Pool', 'Admin', 'Super Admin']);
        });

        Gate::define('access-approver-panel', function (User $user) {
            return $user->hasAnyRole(['Dean', 'Vice President', 'SUC President', 'Admin', 'Super Admin']);
        });
    }
}