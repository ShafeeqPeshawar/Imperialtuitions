<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use App\Mail\CourseEnrollmentConfirmation;
use App\Mail\CourseEnrollmentApproved;
use App\Mail\CourseEnrollmentRejected;

use App\Models\CourseEnrollment;
use Illuminate\Http\Request;

class CourseEnrollmentController extends Controller
{
    // Store new enrollment
    public function store(Request $request)
    {
        $request->validate([
            'course_id'   => 'required|exists:courses,id',
            'course_name' => 'required|string',
            'name'        => 'required|string|max:255',
            'email'       => 'required|email',
            'phone'       => 'nullable|string|max:20',
            'message'     => 'nullable|string',
            'level'       => 'nullable|string|max:50',
            'preferred_date' => 'nullable|date',
            'preferred_time' => 'nullable|date_format:H:i',
        ]);

        $enrollment = CourseEnrollment::create([
            'course_id'   => $request->course_id,
            'course_name' => $request->course_name,
            'name'        => $request->name,
            'email'       => $request->email,
            'phone'       => $request->phone,
            'message'     => $request->message,
            'level'       => $request->level,
            'preferred_date' => $request->preferred_date,
            'preferred_time' => $request->preferred_time,
        ]);

        // Send confirmation email
        Mail::to($enrollment->email)
            ->send(new CourseEnrollmentConfirmation($enrollment));

        return back()->with([
            'popup_success' => true,
            'popup_title'   => 'Enrollment Submitted',
            'popup_message' => 'Thank you for enrolling. An Imperial Tuitions coordinator will contact you shortly.'
        ]);
    }

    // List enrollments by launch
    public function byLaunch($launchId)
    {
        $enrollments = CourseEnrollment::where('launch_id', $launchId)
            ->latest()
            ->get();

        return view('admin.course-enrollments.index', compact('enrollments'));
    }

    // List all enrollments
    public function index()
    {
        $enrollments = CourseEnrollment::latest()->paginate(20);
        return view('admin.course-enrollments.index', compact('enrollments'));
    }

    // Approve enrollment
    public function approve(CourseEnrollment $enrollment)
    {
        $enrollment->update(['status' => 'approved']);

        Mail::to($enrollment->email)
            ->send(new CourseEnrollmentApproved($enrollment));

        return back()->with('success', 'Enrollment approved.');
    }

    // Reject enrollment
    public function reject(CourseEnrollment $enrollment)
    {
        $enrollment->update(['status' => 'rejected']);

        Mail::to($enrollment->email)
            ->send(new CourseEnrollmentRejected($enrollment));

        return back()->with('success', 'Enrollment rejected.');
    }

    // Reply to enrollment inquiry
    public function reply(Request $request, CourseEnrollment $enrollment)
    {
        $request->validate([
            'reply_message' => 'required|string',
        ]);

        Mail::send('emails.course-enrollment-reply', [
            'enrollment' => $enrollment,
            'replyMessage' => $request->reply_message,
        ], function ($mail) use ($enrollment) {
            $mail->to($enrollment->email)
                 ->subject('Imperial Tuitions Training – Enrollment Update');
        });

        return back()->with('success', 'Reply sent successfully.');
    }

    // Show enrollment as JSON
    public function show(CourseEnrollment $enrollment)
    {
        return response()->json([
            'id' => $enrollment->id,
            'course_name' => $enrollment->course_name,
            'name' => $enrollment->name,
            'email' => $enrollment->email,
            'phone' => $enrollment->phone,
            'message' => $enrollment->message,
            'status' => $enrollment->status,
            'level' => $enrollment->level,
            'preferred_date' => $enrollment->preferred_date,
            'preferred_time' => $enrollment->preferred_time,
            'created_at' => $enrollment->created_at->format('d M Y'),
        ]);
    }

    // Delete enrollment
    public function destroy(CourseEnrollment $enrollment)
    {
        $enrollment->delete();
        return back()->with('success', 'Enrollment deleted successfully.');
    }
}