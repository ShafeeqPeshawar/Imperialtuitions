<?php

namespace App\Providers;

use App\Models\Contact;
use App\Models\CourseEnrollment;
use App\Models\CourseInquiry;
use App\Models\CourseLaunch;
use App\Observers\CourseLaunchObserver;
use Illuminate\Support\Facades\View;
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
        CourseLaunch::observe(CourseLaunchObserver::class);

        View::composer('layouts.admin', function ($view) {
            $view->with([
                'pendingEnrollmentsCount' => CourseEnrollment::where('status', 'pending')->count(),
                'pendingContactsCount' => Contact::where('reply_status', 'pending')->count(),
                'pendingInquiriesCount' => CourseInquiry::where('reply_status', 'pending')->count(),
            ]);
        });
    }
}
