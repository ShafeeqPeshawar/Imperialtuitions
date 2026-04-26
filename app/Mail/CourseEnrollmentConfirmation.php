<?php

namespace App\Mail;

use App\Models\CourseEnrollment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CourseEnrollmentConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $enrollment;

    public function __construct(CourseEnrollment $enrollment)
    {
        $this->enrollment = $enrollment;
    }

    public function build()
    {
        return $this
            ->subject('Imperial Tuitions – Registration Received')
            ->view('emails.course-enrollment-confirmation');
    }
}
