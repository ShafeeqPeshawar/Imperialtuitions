@extends('emails.layout')

@section('title', 'Message Received – Imperial Tuitions')

@section('header_title', 'Regarding Your Message')
@section('header_subtitle', 'Thank you for contacting Imperial Tuitions')

@section('body')

<p>Dear <strong>{{ $contact->name }}</strong>,</p>

<p>
Thank you for contacting <strong>Imperial Tuitions</strong>. We have received your message and appreciate the opportunity to assist you.
</p>

<strong>Our Reply</strong>
{{-- ADMIN REPLY MESSAGE BOX --}}
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
Our team will review your message and contact you shortly. If your inquiry is related to a specific course, feel free to reply to this email with the course name and any additional details.
</p>

<div class="email-signature">
    <div class="email-signature-line1">Warm regards,</div>
    <div class="email-signature-line2">Imperial Tuitions – Support Team</div>
    <div class="email-signature-line3">
        🌐 <a href="https://phplaravel-1208793-6158387.cloudwaysapps.com/" style="text-decoration:none;">Imperial Tuitions</a>
    </div>
</div>

@endsection