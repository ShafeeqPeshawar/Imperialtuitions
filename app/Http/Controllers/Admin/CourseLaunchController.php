<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseLaunch;
use Illuminate\Http\Request;

class CourseLaunchController extends Controller
{


 public function index()
{
    $launches = CourseLaunch::whereHas('course', function ($q) {
            $q->where('price', 0);
        })
        ->with('course')
        ->withCount([
            'enrollments as enrollments_count' => function ($q) {
                $q->where('status', 'pending');
            },
            'inquiries as inquiries_count' => function ($q) {
                $q->whereNull('reply_status')
                  ->orWhere('reply_status', '!=', 'replied');
            }
        ])
        ->orderBy('launch_date')
        ->get();

    return view('admin.course-launches.index', compact('launches'));
}






   public function create()
{
    $courses = Course::where('price', 0)
        ->orderBy('title')
        ->get();

    return view('admin.course-launches.create', compact('courses'));
}

 

public function store(Request $request)
{
    $request->validate([
        'course_id' => [
            'required',
            'exists:courses,id',
            function ($attr, $value, $fail) {
                if (!Course::where('id', $value)->where('price', 0)->exists()) {
                    $fail('Only free courses can be launched.');
                }
            }
        ],
        'launch_date' => 'required|date',
    ]);

    CourseLaunch::create([
        'course_id'   => $request->course_id,
        'launch_date' => $request->launch_date,
    ]);

    // Subscriber emails are sent by CourseLaunchObserver (queued) for free courses only.

    return redirect()
        ->route('admin.course-launches.index')
        ->with('success', 'Launch date added. Subscribers will be notified (free courses only).');
}
    public function edit(CourseLaunch $courseLaunch)
{
    $courses = Course::where('price', 0)
        ->orderBy('title')
        ->get();

    return view('admin.course-launches.edit', compact('courseLaunch', 'courses'));
}


    public function update(Request $request, CourseLaunch $courseLaunch)
    {
        $request->validate([
            'course_id'   => 'required|exists:courses,id',
            'launch_date' => 'required|date',
        ]);

        $courseLaunch->update([
            'course_id'   => $request->course_id,
            'launch_date' => $request->launch_date,
        ]);

        return redirect()
            ->route('admin.course-launches.index')
            ->with('success', 'Launch date updated.');
    }

    public function destroy(CourseLaunch $courseLaunch)
    {
        $courseLaunch->delete();

        return back()->with('success', 'Launch date deleted.');
    }
}
