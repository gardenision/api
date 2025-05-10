<?php

namespace App\Providers;

use App\Listeners\AssignRole;
use App\Models\Device;
use App\Models\DeviceToken;
use App\Models\DeviceType;
use App\Models\Garden;
use App\Models\GardenDevice;
use App\Models\Log;
use App\Models\Module;
use App\Models\Project;
use App\Policies\DevicePolicy;
use App\Policies\DeviceTokenPolicy;
use App\Policies\DeviceTypePolicy;
use App\Policies\GardenDevicePolicy;
use App\Policies\GardenPolicy;
use App\Policies\LogPolicy;
use App\Policies\ModulePolicy;
use App\Policies\PersonalAccessTokenPolicy;
use App\Policies\ProjectPolicy;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\PersonalAccessToken;

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
        Gate::policies([
            Device::class => DevicePolicy::class,
            DeviceType::class => DeviceTypePolicy::class,
            Garden::class => GardenPolicy::class,
            GardenDevice::class => GardenDevicePolicy::class,
            Module::class => ModulePolicy::class,
            Project::class => ProjectPolicy::class,
            Log::class => LogPolicy::class,
            DeviceToken::class => DeviceTokenPolicy::class,
        ]);

        Event::listen(
            AssignRole::class,
        );
    }
}
