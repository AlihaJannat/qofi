<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Mail\TestMail;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Events\Verified;


class EmailVerificationController extends Controller
{
    public function sendTestEmail()
    {
        // $to = 'alihanazeer@example.com'; // Replace with the recipient's email address
        $to = 'umerarain1a@gmail.com';
        Mail::to($to)->send(new TestMail());

        return 'Test email sent successfully! at ' .$to ;
    }

    public function verify(EmailVerificationRequest $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('home')->with('message', 'Your email is already verified.');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect()->route('home')->with('message', 'Your email has been verified.');
    }

    public function resend(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();

        return redirect()->back()->with('message', 'Verification link sent!');
    }
}

