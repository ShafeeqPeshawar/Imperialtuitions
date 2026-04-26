@extends('emails.layout')

@section('title', 'Enrollment Update – Imperial Tuitions')

@section('header_title', 'Enrollment Update')
@section('header_subtitle', 'Thank you for your interest in our training programs')

@section('body')

<p>Dear <strong>{{ $enrollment->name }}</strong>,</p>

<p>Thank you for your interest in the <strong>{{ $enrollment->course_name }}</strong> training program.</p>

<p>
After reviewing your registration, we regret to inform you that we are unable to proceed with your enrollment for this particular session at this time.
</p>

<p>
This decision may be due to limited seat availability, scheduling constraints, or eligibility requirements for the selected batch.
</p>

<p>
We truly appreciate your interest in <strong>Imperial Tuitions Training</strong> and encourage you to explore upcoming sessions or reach out to us if you would like guidance on alternative training options.
</p>

<p>
If you have any questions or would like further clarification, please feel free to reply to this email.
</p>

<div class="email-signature">
    <div class="email-signature-line1">Kind regards,</div>
    <div class="email-signature-line2">Imperial Tuitions – Training Team</div>
    <div class="email-signature-line3">
        🌐 <a href="https://phplaravel-1208793-6158387.cloudwaysapps.com/" style="text-decoration:none;">Imperial Tuitions</a>
    </div>
</div>

@endsection