@extends('emails.layout')

@section('title', 'Regarding Your Message – Imperial Tuitions')

@section('header_title', 'Regarding Your Message')
@section('header_subtitle', 'Thank you for contacting Imperial Tuitions')

@section('body')

<p>Dear <strong>{{ $inquiry->name }}</strong>,</p>

<p>
Thank you for contacting <strong>Imperial Tuitions</strong>. We have received your inquiry regarding <strong>{{ $inquiry->course_title }}</strong> and appreciate the opportunity to assist you.
</p>

<strong>Our Reply</strong>
@if(!empty($replyMessage))
<div style="
    background:#f9f9f9;
    border-left:4px solid #fbbf24;
    padding:12px 16px;
    margin:16px 0;
    border-radius:4px;
">
    <p style="margin-top:6px;">
        {{ $replyMessage }}
    </p>
</div>
@endif

<p>
If you have any further questions or require clarification, feel free to reply to this email.
</p>

<div class="email-signature">
    <div class="email-signature-line1">Warm regards,</div>
    <div class="email-signature-line2">Imperial Tuitions – Support Team</div>
    <div class="email-signature-line3">
        🌐 <a href="https://phplaravel-1208793-6158387.cloudwaysapps.com/" style="text-decoration:none;">Imperial Tuitions</a>
    </div>
</div>

@endsection 