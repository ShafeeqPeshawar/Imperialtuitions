<?php

namespace App\Http\Controllers;

// app/Http/Controllers/SubscriberController.php

use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
 use App\Mail\SubscriberWelcomeMail;

use App\Mail\SubscriberBroadcastMail;


class SubscriberController extends Controller
{

public function store(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'name' => 'required|string', // ← add this
    ]);

    $exists = Subscriber::where('email', $request->email)->exists();

    if ($exists) {
        return response()->json([
            'message' => 'Already subscribed'
        ], 409);
    }

    $subscriber = Subscriber::create([
        'email' => $request->email,
        'name' => $request->name, // ← save name
    ]);

    Mail::to($subscriber->email)->send(
        new SubscriberWelcomeMail($subscriber->name)
    );

    return response()->json([
        'success' => true,
        'message' => 'Subscribed successfully. Please check your email.'
    ]);
}

    public function index()
    {
        $subscribers = Subscriber::latest()->get();
        return view('admin.subscribers.index', compact('subscribers'));
    }
//    
public function sendMessage(Request $request)
{
    $request->validate([
        'emails' => 'required|array',
        'message' => 'required|string'
    ]);

    // Get subscribers from DB
 // Get subscribers from DB
$subscribers = Subscriber::whereIn('email', $request->emails)->get();

// Loop through each subscriber and send broadcast email
foreach ($subscribers as $subscriber) {
    $name = $subscriber->name ?? 'Subscriber'; // fallback if name is null
    $content = $request->message;              // message content

    try {
        Mail::to($subscriber->email)->send(
            new SubscriberBroadcastMail($name, $content)
        );
    } catch (\Exception $e) {
        // Optional: log failed email sending
        \Log::error("Failed to send email to {$subscriber->email}: " . $e->getMessage());
    }
}

    return response()->json([
        'message' => 'Message sent successfully'
    ]);
}

}