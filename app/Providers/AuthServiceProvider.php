<?php

namespace App\Providers;

use App\Models\EarningsIncentivesSheet;
use App\Policies\EarningIncentiveSheetPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        'App\Models\Model' => 'App\Policies\ModelPolicy',
        'App\Models\Status::class' => 'App\Policies\StatusPolicy::class',
        'App\Models\Order::class' => 'App\Policies\OrderPolicy::class',
        EarningsIncentivesSheet::class => EarningIncentiveSheetPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        if (! $this->app->routesAreCached()) {
            Passport::routes();
        }
    }
}
