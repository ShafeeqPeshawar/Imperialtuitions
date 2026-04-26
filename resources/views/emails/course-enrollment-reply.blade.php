@extends('emails.layout')

@section('title', 'Enrollment Update – Imperial Tuitions')

@section('header_title', 'Enrollment Update')
@section('header_subtitle', 'We are here to assist you')

@section('body')

<p>Dear <strong>{{ $enrollment->name }}</strong>,</p>

<p>
{{ $replyMessage }}
</p>

<p>
If you need any further assistance regarding your enrollment for <strong>{{ $enrollment->course_name }}</strong>, please feel free to reply to this email.
</p>

<div class="email-signature">
    <div class="email-signature-line1">Kind regards,</div>
    <div class="email-signature-line2">Imperial Tuitions Training Team</div>
    <div class="email-signature-line3">
        🌐 <a href="https://phplaravel-1208793-6158387.cloudwaysapps.com/" style="text-decoration:none;">Imperial Tuitions</a>
    </div>
</div>

@endsection