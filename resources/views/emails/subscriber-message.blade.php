@extends('emails.layout')

@section('header_title', 'Thank You for Subscribing!')
@section('header_subtitle', 'You will now receive updates from Imperial Tuitions.')

@section('body')
    <p>Dear {{ $name }},</p>
    <p>We are excited to keep you informed about our latest courses and offers.</p>

    <!-- Subscriber Message Box -->
    <div class="email-message-box">
        <div class="email-message-label"> Message</div>
        <div class="email-message-content" style="white-space: pre-line;">
            {{ nl2br(strip_tags($content)) }}
        </div>
    </div>

    <p class="email-signature">
        Kind regards,<br>
        <strong>Imperial Tuitions Team</strong>
    </p>
@endsection