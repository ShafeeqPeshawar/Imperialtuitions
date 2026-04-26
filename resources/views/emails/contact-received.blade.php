@extends('emails.layout')

@section('title', 'Message Received – Imperial Tuitions')

@section('preheader')
We've received your message. Our team will contact you shortly.
@endsection

@section('header_title', 'Message Received')
@section('header_subtitle', 'Thank you for reaching out — we will get back to you soon.')

@section('body')
<p>Dear <strong>{{ $contact->name }}</strong>,</p>

<p>Thank you for contacting <strong>Imperial Tuitions</strong>. We have received your message and appreciate the opportunity to assist you.</p>

<div class="email-message-box" style="background:#f9f9f9; border-left:4px solid #fefdfa; padding:12px 16px; margin:16px 0; border-radius:4px;">
    <div class="email-message-label" style="font-weight:600; margin-bottom:4px;">Your Message</div>
    <div class="email-message-content">{{ $contact->message }}</div>
</div>

<p>Our team will review your message and contact you shortly. If your inquiry is related to a specific course, feel free to reply to this email with the course name and any additional details.</p>

<p>Thank you for your interest in <strong>Imperial Tuitions</strong>. We look forward to supporting you.</p>

<div class="email-signature" style="margin-top:24px;">
    <div class="email-signature-line1">Kind regards,</div>
    <div class="email-signature-line2">
    <a href="https://phplaravel-1208793-6158387.cloudwaysapps.com/" style="text-decoration:none;">Imperial Tuitions</a>
</div>
    <div class="email-signature-line3">Training &amp; Support Team</div>
</div>
@endsection

@section('footer_note')
This is an automated confirmation that we received your message. If you did not request this, you may ignore this email.
@endsection