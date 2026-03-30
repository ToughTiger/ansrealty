<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Lead;
use App\Observers\LeadObserver;

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
        // Register observers for automation
        Lead::observe(LeadObserver::class);
        \App\Models\SiteVisit::observe(\App\Observers\SiteVisitObserver::class);
        \App\Models\Opportunity::observe(\App\Observers\OpportunityObserver::class);
        \App\Models\Booking::observe(\App\Observers\BookingObserver::class);
        \App\Models\Task::observe(\App\Observers\TaskObserver::class);
    }
}
