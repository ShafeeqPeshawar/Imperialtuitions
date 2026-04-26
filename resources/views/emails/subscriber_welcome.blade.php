@extends('emails.layout')

@section('title', 'Welcome to Imperial Tuitions')

@section('header_title', 'Subscription Confirmed')

@section('header_subtitle', 'You are now subscribed to Imperial Tuitions updates')

@section('body')

<p>Dear {{ $name }},</p>

<p>
Thank you for subscribing to <strong>Imperial Tuitions</strong>.
</p>

<p>
You’ll now receive updates about our latest training programs,
free workshops, special offers, and technology insights straight to your inbox.
</p>

<p>
We’re excited to stay connected and help you grow professionally.
</p>

<div class="email-signature">
    <div class="email-signature-line1">Warm regards,</div>
    <div class="email-signature-line2">Imperial Tuitions – Marketing Team</div>
    <div class="email-signature-line3">
        🌐 https://phplaravel-1208793-6158387.cloudwaysapps.com/
    </div>
</div>

@endsection