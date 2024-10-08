<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\SwShop;
use App\Models\SwSubscription;
use App\Models\SwSubscriptionShop;
use App\Models\SwUserSubscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{

    /**
     * Undocumented function
     *
     * @return void
     */
    public function index()
    {
        return view('admin.subscription.index');
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return void
     */
    public function add(Request $request)
    {

        // Validate the request data
        $validated = $request->validate([
            'name' => 'required|max:255',
            'short_description' => 'nullable|max:500',
            'long_description' => 'nullable',
            'duration' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'shops_count' => 'required|integer|min:0',
            'cups_count' => 'required|integer|min:0',
            'coffee_type' => 'required|regex:/^\d+(-\d+)?$/'
        ]);

        $subscription = SwSubscription::create([
            'name' => $validated['name'],
            'short_description' => $validated['short_description'],
            'long_description' => $validated['long_description'],
            'duration' => $validated['duration'],
            'price' => $validated['price'],
            'shops_count' => $validated['shops_count'],
            'cups_count' => $validated['cups_count'],
            'coffee_type' => $validated['coffee_type'] // New field
        ]);

        return response()->json('Subscription created successfully');
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return void
     */
    public function update(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'name' => 'required|max:255',
            'short_description' => 'nullable|max:500',
            'long_description' => 'nullable',
            'duration' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'shops_count' => 'required|integer|min:0',
            'cups_count' => 'required|integer|min:0',
            'coffee_type' => 'required|regex:/^\d+(-\d+)?$/'
        ]);

        $subscription = SwSubscription::findorfail($request->id);
        $subscription->update($validated);


        return response()->json('done');
    }

    public function changeStatus(Request $request)
    {
        $subscription = SwSubscription::findorfail($request->id);
        $subscription->status = $request->status;
        $subscription->save();

        return response()->json('done');
    }

    public function delete(Request $request)
    {

        $subscription = SwSubscription::findorfail($request->id);
        if ($subscription) {
            $subscription->delete();
        }

        return response()->json('done');
    }

    public function subscribersIndex()
    {
        return view('admin.subscription.subscribers');
    }
    public function subscriberChangeStatus(Request $request)
    {
        $subscription = SwUserSubscription::findorfail($request->id);
        $subscription->status = $request->status;
        $subscription->save();

        return response()->json('done');
    }

    // public function add(Request $request)
    // {

    //     $orderPerDay = config('constant.ORDERPERDAY');
    //     // Validate the request data
    //     $validated = $request->validate([
    //         'name' => 'required|max:255',
    //         'description' => 'nullable',
    //         'duration' => 'required|numeric|min:0',
    //         'price' => 'required|numeric|min:0',
    //         'shop_id' => 'required|array',
    //         'shop_counts' => 'required|array',
    //         'shop_counts.*' => 'numeric|min:0'
    //     ]);

    //     // Check if all shop counts are provided and sum does not exceed the duration
    //     $totalCount = array_sum($validated['shop_counts']);

    //     if ($totalCount < $validated['duration']) {
    //         return response()->json(['message' => 'Total shop Order counts Should be equal to ' . ($orderPerDay * $validated['duration'])], 400);
    //     } else if ($totalCount != ($orderPerDay * $validated['duration'])) {
    //         return response()->json(['message' => 'Total shop Order counts Should be equal to ' . ($orderPerDay * $validated['duration'])], 400);
    //     }

    //     // Create the subscription
    //     $subscription = SwSubscription::create([
    //         'name' => $validated['name'],
    //         'description' => $validated['description'],
    //         'duration' => $validated['duration'],
    //         'price' => $validated['price'],
    //     ]);

    //     // Attach shops with their counts
    //     foreach ($validated['shop_id'] as $shopId) {
    //         $count = $validated['shop_counts'][$shopId] ?? 0;

    //         if ($count > 0) {
    //             SwSubscriptionShop::create([
    //                 'sw_subscription_id' => $subscription->id,
    //                 'sw_shop_id' => $shopId,
    //                 'order_count' => $count
    //             ]);
    //         }
    //     }

    //     return response()->json('Subscription created successfully');
    // }


    // public function update(Request $request)
    // {

    //     $orderPerDay = config('constant.ORDERPERDAY');

    //     $validated = $request->validate([
    //         'name' => 'required|max:255',
    //         'description' => 'nullable',
    //         'duration' => 'required|numeric',
    //         'price' => 'required|numeric',
    //         'shop_id' => 'required|array',
    //         'shop_counts' => 'required|array',
    //         'shop_counts.*' => 'numeric|min:0'
    //     ]);

    //     // Check if all shop counts are provided and sum does not exceed the duration
    //     $totalCount = array_sum($validated['shop_counts']);

    //     if ($totalCount < $validated['duration']) {
    //         return response()->json(['message' => 'Total shop Order counts Should be equal to ' . ($orderPerDay * $validated['duration'])], 400);
    //     } else if ($totalCount != ($orderPerDay * $validated['duration'])) {
    //         return response()->json(['message' => 'Total shop Order counts Should be equal to ' . ($orderPerDay * $validated['duration'])], 400);
    //     }

    //     $subscription = SwSubscription::findorfail($request->id);
    //     $subscription->update($validated);

    //     //deleting all relation in pivot table
    //     $subscription->subscriptionShops()->delete();
    //     //adding them again
    //     foreach ($validated['shop_id'] as $shopId) {
    //         $count = $validated['shop_counts'][$shopId] ?? 0;

    //         if ($count > 0) {
    //             SwSubscriptionShop::create([
    //                 'sw_subscription_id' => $subscription->id,
    //                 'sw_shop_id' => $shopId,
    //                 'order_count' => $count
    //             ]);
    //         }
    //     }

    //     return response()->json('done');
    // }

}
