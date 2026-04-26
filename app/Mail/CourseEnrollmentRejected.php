<?php

namespace App\Mail;

use App\Models\CourseEnrollment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CourseEnrollmentRejected extends Mailable
{
    use Queueable, SerializesModels;

    public $enrollment;

    public function __construct(CourseEnrollment $enrollment)
    {
        $this->enrollment = $enrollment;
    }

    public function build()
    {
        return $this->subject('Enrollment Update – Imperial Tuitions')
                    ->view('emails.course-enrollment-rejected'); // Blade template
    }
}