@extends('emails.layout')

@section('title', 'Enrollment Approved – Imperial Tuitions')

@section('header_title', 'Enrollment Approved')
@section('header_subtitle', 'Thank you for your interest — your enrollment is now confirmed!')

@section('body')

<p>Dear <strong>{{ $enrollment->name }}</strong>,</p>

<p>We’re pleased to inform you that your enrollment has been approved.</p>

<strong>Your Training Details</strong>
{{-- COURSE DETAILS BOX --}}
<div style="
background:#f9f9f9;
border-left:4px solid #fbbf24;
padding:12px 16px;
margin:16px 0;
border-radius:4px;">
    <p style="margin-top:6px;">
        Course: {{ $enrollment->course_name }}<br>
        Level: {{ $enrollment->level ? ucfirst($enrollment->level) : '—' }}
    </p>
</div>

<p>Our team will contact you shortly with further information regarding schedule, access, and payment.</p>

<p>If you have any questions in the meantime, feel free to reply to this email.</p>

<div class="email-signature">
    <div class="email-signature-line1">Warm regards,</div>
    <div class="email-signature-line2">Imperial Tuitions – Training Team</div>
    <div class="email-signature-line3">
        🌐 <a href="https://phplaravel-1208793-6158387.cloudwaysapps.com/" style="text-decoration:none;">Imperial Tuitions</a>
    </div>
</div>

@endsection