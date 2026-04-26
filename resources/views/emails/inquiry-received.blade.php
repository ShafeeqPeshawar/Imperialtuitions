@extends('emails.layout')

@section('title', 'Inquiry Received – Imperial Tuitions')
@section('header_title')
Inquiry Received
@endsection
@section('preheader')
We've received your inquiry. Our team will contact you shortly.
@endsection

@section('body')


<!-- HEADER TITLE: INQUIRY RECEIVED -->


<!-- HEADER SUBTITLE -->
<div style="
    font-family: Arial, sans-serif;
    font-size: 16px !important;
    color: #cbd5e1 !important;
    margin-bottom: 20px;
">
    Thank you for reaching out — we'll be in touch shortly.
</div>

<!-- BODY -->
<p style="font-family:Arial, sans-serif; margin-bottom:16px;">
    Dear <strong>{{ $inquiry->name }}</strong>,
</p>

<p style="font-family:Arial, sans-serif; margin-bottom:16px;">
    Thank you for contacting <strong>Imperial Tuitions</strong> regarding our professional training programs. 
    We have successfully received your inquiry and will review it carefully.
</p>

<!-- DETAILS TABLE -->
<table role="presentation" width="100%" style="border-collapse: collapse; margin:3px 0;">
    <tr>
        <td style="font-family:Arial, sans-serif; font-weight:700; color:#64748b; padding:2px 0; width:120px;">Course</td>
        <td style="font-family:Arial, sans-serif; font-weight:600; color:#0f172a; padding:2px 0;">{{ $inquiry->course_title }}</td>
    </tr>
    <tr>
        <td style="font-family:Arial, sans-serif; font-weight:700; color:#64748b; padding:2px 0;">Level</td>
        <td style="font-family:Arial, sans-serif; font-weight:600; color:#0f172a; padding:2px 0;">
            {{ $inquiry->level ? ucfirst($inquiry->level) : '—' }}
        </td>
    </tr>
</table>

<!-- INQUIRY HEADING -->
<p style="
    font-family:Arial, sans-serif;
    font-size:14px !important;
    font-weight:700 !important;
    margin:8px 0 6px;
    color:#0f172a;
">
    Your Inquiry
</p>

<!-- MESSAGE BOX -->
<div style="
    font-family:Arial, sans-serif;
    margin:6px 0 14px;
    padding:14px 16px;
    background:#f8fafc;
    border:1px solid #0b1220;
    border-radius:8px;
    font-size:14px !important;
    line-height:1.6 !important;
    color:#0f172a;
    white-space: pre-line;
">
    {{ $inquiry->message }}
</div>

<p style="font-family:Arial, sans-serif; margin:16px 0;">
    Our team will review your inquiry and contact you shortly with further details. 
    We appreciate your interest in <strong>Imperial Tuitions</strong> and look forward to supporting your professional growth.
</p>

<!-- SIGNATURE -->
<div style="font-family:Arial, sans-serif; margin-top:32px; padding-top:16px; border-top:1px solid #e2e8f0;">
    <p style="margin:0; font-weight:600; color:#0f172a;">Kind regards,</p>
   <p style="margin:4px 0 0; font-weight:700; color:#0f172a;">
    <a href="https://phplaravel-1208793-6158387.cloudwaysapps.com/" style="color:#0f172a; text-decoration:none;">Imperial Tuitions</a>
</p>
    <p style="margin:2px 0 0; color:#64748b; font-size:13px;">Training & Support Team</p>
</div>

<!-- FOOTER -->
<p style="font-family:Arial, sans-serif; font-size:12px; color:#64748b; line-height:1.5; margin-top:20px;">
This is an automated confirmation that we received your inquiry. If you did not request this, you may ignore this message.
</p>
<p style="font-family:Arial, sans-serif; font-size:12px; color:#64748b; line-height:1.5; margin-top:12px;">
© 2026 <a href="https://phplaravel-1208793-6158387.cloudwaysapps.com/" style="color:#64748b; text-decoration:none; font-weight:600;">Imperial Tuitions</a>. All rights reserved.
</p>

@endsection