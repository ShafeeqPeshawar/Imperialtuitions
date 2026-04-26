@extends('emails.layout')

@section('title', 'Registration Received – Imperial Tuitions')

@section('preheader')
We've received your registration. Our team will contact you shortly to confirm schedule and payment.
@endsection

@section('header_title', 'Registration Received')
@section('header_subtitle', 'Thank you for your interest — we\'ll be in touch shortly.')

@section('body')
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="font-family:Arial,sans-serif;">
<tr>
    <td style="padding:0 20px;">

        <p>Dear <strong>{{ $enrollment->name }}</strong>,</p>

        <p>Thank you for contacting <strong>Imperial Tuitions</strong> and for your interest in one of our professional training programs.
        We have successfully received your registration and appreciate the opportunity to assist you.</p>

        <div style="margin:20px 0; padding:15px; background:#f9fafb; border:1px solid #e5e7eb; border-radius:6px;">
            <div style="font-weight:600; margin-bottom:10px; color:#111827;">Course Details</div>
            <table role="presentation" width="100%" style="border-collapse:collapse; font-size:14px;">
                <tr>
                    <td style="width:140px; padding:6px 0; color:#64748b;">Course</td>
                    <td style="padding:6px 0; color:#0f172a; font-weight:600;">{{ $enrollment->course_name }}</td>
                </tr>
                <tr>
                    <td style="width:140px; padding:6px 0; color:#64748b;">Level</td>
                    <td style="padding:6px 0; color:#0f172a; font-weight:600;">{{ $enrollment->level ? ucfirst($enrollment->level) : '—' }}</td>
                </tr>
            </table>
        </div>

        <p>Our team will review your registration and will contact you shortly to confirm availability, schedule, and payment details.</p>

        <p>Thank you for your interest in learning with <strong>Imperial Tuitions</strong>. We look forward to supporting you in your <strong>professional development journey</strong>.</p>

        <div style="margin-top:30px; font-size:14px; color:#111827; line-height:1.5;">
            <div>Kind regards,</div>
            <div> 🌐<a href="https://phplaravel-1208793-6158387.cloudwaysapps.com/" target="_blank" style="color:#111827; text-decoration:none;">Imperial Tuitions</a></div>
            <div>Training &amp; Support Team</div>
        </div>

    </td>
</tr>
</table>
@endsection

@section('footer_note')
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="font-family:Arial,sans-serif; font-size:12px; color:#64748b; padding:10px 20px; text-align:center;">
<tr>
    <td>
        This is an automated confirmation that we received your registration. If you did not request this, you may ignore this message.
    </td>
</tr>
</table>
@endsection