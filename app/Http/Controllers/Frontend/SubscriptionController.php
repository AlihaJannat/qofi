<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\SwOrderPayment;
use App\Models\SwSubscription;
use App\Models\SwSubscriptionPayment;
use App\Models\SwUserSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    public function index()
    {
        // Fetch active subscriptions
        $subscriptions = SwSubscription::where('status', 1)->get();
        return view('frontend.subscriptions.index', compact('subscriptions'));
    }

    public function subscribe(Request $request, $subscriptionId)
    {
        $subscription = SwSubscription::findOrFail($subscriptionId);

        // Create a new UserSubscription record
        $userSubscription = SwUserSubscription::create([
            'user_id' => Auth::user()->id,
            'sw_subscription_id' => $subscription->id,
            'date' => now(),
            'status' => 1,
        ]);

        // Create a new SubscriptionPayment record
        $subscriptionPayment = SwSubscriptionPayment::create([
            'user_id' => Auth::id(),
            'currency' => 'KWD',
            'charge_id' => 'CHARGE_ID',
            'payment_channel' => 'Stripe',
            'amount' => $subscription->price,
            'payment_status' => SwOrderPayment::STATUS_PAID,
            'status' => 1,
        ]);

        // Update the user subscription with the payment ID
        $userSubscription->sw_subscription_payment_id = $subscriptionPayment->id;
        $userSubscription->save();

        return redirect()->route('subscriptions.index')->with('success', 'Subscription successful!');
    }
}
