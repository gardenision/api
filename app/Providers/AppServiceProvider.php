<?php

namespace App\Providers;

use App\Models\Analytic;
use App\Models\Setting;
use App\Policies\AnalyticPolicy;
use App\Policies\DeviceSettingPolicy;
use Illuminate\Support\Facades\Gate;
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
        Gate::policy(Setting::class, DeviceSettingPolicy::class);
        Gate::policy(Analytic::class, AnalyticPolicy::class);
    }
}
