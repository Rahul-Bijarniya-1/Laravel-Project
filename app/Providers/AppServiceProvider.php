<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Events\UserCreated;
use App\Listeners\NotifyUserCreated;
use App\Listeners\NotifyMail;
use Illuminate\Support\Facades\Event;

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

        // Event::listen(
        //     UserCreated::class,
        //     [NotifyUserCreated::class,
        //     NotifyMail::class]
        // );
    }
}
