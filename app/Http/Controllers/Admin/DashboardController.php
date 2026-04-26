<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\CourseInquiry;
use App\Models\Contact;
use Illuminate\Support\Collection;

class DashboardController extends Controller
{
    public function index()
    {
        // KPI counts
        $totalEnrollments = CourseEnrollment::count();
        $totalActiveCourses = Course::where('is_active', 1)->count();
        // Pending Inquiries Counts
$pendingInquiriesCount = CourseInquiry::where('reply_status', 'pending')->count();
$pendingContactsCount = Contact::where('reply_status', 'pending')->count();

        // Recent Enrollments
        $enrollments = CourseEnrollment::latest()
            ->take(3)
            ->get()
            ->map(function ($e) {
                return [
                    'activity' => 'New enrollment in ' . $e->course_name,
                    'date' => $e->created_at,
                    'status' => ucfirst($e->status ?? 'pending'),
                    'status_color' => $e->status === 'approved' ? 'green' : 'yellow',
                ];
            });

        // Recent Course Inquiries
        $courseInquiries = CourseInquiry::latest()
            ->take(3)
            ->get()
            ->map(function ($i) {
                return [
                    'activity' => 'New course inquiry: ' . $i->course_title,
                    'date' => $i->created_at,
                    'status' => ucfirst($i->reply_status),
                    'status_color' => $i->reply_status === 'replied' ? 'green' : 'yellow',
                ];
            });

        // Recent Contact Inquiries
        $contacts = Contact::latest()
            ->take(3)
            ->get()
            ->map(function ($c) {
                return [
                    'activity' => 'New contact inquiry from ' . $c->name,
                    'date' => $c->created_at,
                    'status' => ucfirst($c->reply_status),
                    'status_color' => $c->reply_status === 'replied' ? 'green' : 'yellow',
                ];
            });

        // Merge & sort all activities
        $recentActivities = $enrollments
            ->merge($courseInquiries)
            ->merge($contacts)
            ->sortByDesc('date')
            ->take(5)
            ->values();

        return view('dashboard', compact(
            'totalEnrollments',
            'totalActiveCourses',
            'recentActivities',
             'pendingInquiriesCount',
    'pendingContactsCount'
        ))->with('title', 'Dashboard');
    }
}
