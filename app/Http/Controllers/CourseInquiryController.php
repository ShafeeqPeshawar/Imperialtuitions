<?php

namespace App\Http\Controllers;

use App\Models\CourseInquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CourseInquiryController extends Controller
{
    /* =========================
       ADMIN: LIST
    ========================== */
    public function index()
    {
        $inquiries = CourseInquiry::latest()->paginate(20);
        return view('admin.course-inquiries.index', compact('inquiries'));
    }

    /* =========================
       ADMIN: VIEW (AJAX MODAL)
    ========================== */
    public function show(CourseInquiry $courseInquiry)
    {
        if (!$courseInquiry->is_viewed) {
            $courseInquiry->update(['is_viewed' => true]);
        }

        return response()->json([
            'id' => $courseInquiry->id,
            'course_title' => $courseInquiry->course_title,
            'name' => $courseInquiry->name,
            'email' => $courseInquiry->email,
            'phone' => $courseInquiry->phone,
            'message' => $courseInquiry->message,
            'level' => $courseInquiry->level,
            'launch_date' => $courseInquiry->launch_date,
        ]);
    }

    /* =========================
       ADMIN: REPLY
    ========================== */
    public function reply(Request $request, CourseInquiry $courseInquiry)
    {
        $request->validate([
            'reply_message' => 'required|string',
        ]);

        Mail::send('emails.course-inquiry-reply', [
            'inquiry' => $courseInquiry,
            'replyMessage' => $request->reply_message,
        ], function ($mail) use ($courseInquiry) {
            $mail->to($courseInquiry->email)
                ->subject('Imperial Tuitions – Inquiry Response: ' . $courseInquiry->course_title);
        });

        $courseInquiry->update([
            'reply_status' => 'replied',
        ]);

        return redirect()
            ->route('admin.course-inquiries.index')
            ->with('success', 'Reply sent successfully.');
    }

    public function byLaunch($launchId)
    {
        $inquiries = CourseInquiry::where('launch_id', $launchId)
            ->latest()
            ->paginate(20);

        return view('admin.course-inquiries.index', compact('inquiries'));
    }

    /* =========================
       FRONTEND: STORE + EMAIL
    ========================== */
    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'launch_id' => 'nullable|exists:course_launches,id',
            'course_title' => 'required|string',
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'message' => 'required|string',
            'level' => 'nullable|string|max:50',
            'launch_date' => 'nullable|date',
        ]);

        $inquiry = CourseInquiry::create([
            'course_id' => $request->course_id,
            'launch_id' => $request->launch_id,
            'course_title' => $request->course_title,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'message' => $request->message,
            'level' => $request->level,
            'launch_date' => $request->launch_date,
        ]);

        Mail::send(
            'emails.inquiry-received',
            ['inquiry' => $inquiry],
            function ($mail) use ($inquiry) {
                $mail->to($inquiry->email)
                    ->subject('Imperial Tuitions – Inquiry Received');
            }
        );

        return back()->with([
            'popup_success' => true,
            'popup_title' => 'Inquiry Sent',
            'popup_message' => 'Your inquiry has been received. Our team will respond within 24 hours.',
        ]);
    }

    public function destroy(CourseInquiry $courseInquiry)
    {
        $courseInquiry->delete();

        return redirect()
            ->back()
            ->with('success', 'Inquiry deleted successfully.');
    }
}
