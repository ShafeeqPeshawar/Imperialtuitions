{{-- resources/views/emails/subscriber_broadcast.blade.php --}}

@extends('emails.layout')

@section('title', 'Message from Imperial Tuitions')

@section('header_title', 'You Have a New Message!')
@section('header_subtitle', 'Stay updated with the latest from Imperial Tuitions')

@section('body')

    {{-- Personalized greeting --}}
    <p>Dear {{ $name }},</p>

    <p>We are excited to share the following message with you:</p>

    <!-- Subscriber Message Box -->
    <div class="email-message-box" style="border:1px solid #ddd; padding:10px; border-radius:5px; margin-top:15px;">
        <div class="email-message-label" style="font-weight:bold; margin-bottom:5px;">Message</div>
        <div class="email-message-content" style="white-space: pre-line; line-height:1.5;">
            {!! nl2br(e($content)) !!}
        </div>
    </div>

    <p class="email-signature" style="margin-top:20px;">
        Kind regards,<br>
        <strong>Imperial Tuitions Team</strong>
    </p>

@endsection