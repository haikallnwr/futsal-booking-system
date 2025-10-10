<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\Gor;
use App\Policies\GorPolicy;
use App\Models\Field;
use App\Policies\FieldPolicy;
use App\Models\Order;
use App\Policies\OrderPolicy;
use App\Models\Schedule;
use App\Policies\SchedulePolicy;
use App\Models\GorImage;
use App\Policies\GorImagePolicy;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
     /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Gor::class => GorPolicy::class,
        Field::class => FieldPolicy::class,
        Order::class => OrderPolicy::class,
        Schedule::class => SchedulePolicy::class,
        GorImage::class => GorImagePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
