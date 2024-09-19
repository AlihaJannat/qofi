<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\SwOrder;
use App\Models\SwOrderProduct;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = SwOrder::all();
        return view('admin.orders.index');
    }


    public function edit(SwOrder $order)
    {
        $customer = $order->user;
        $products = $order->getShopsWithOrderDetails();
        $order->product = $products;
        $address = $order->address;

        return view('admin.orders.edit', compact('order', 'customer', 'address'));
    }

    public function updateStatus(Request $request, $orderId)
    {
        $order = SwOrder::findOrFail($orderId);

        $validatedData = $request->validate([
            'order_status' => 'required|in:' . implode(',', [
                SwOrder::STATUS_PENDING,
                SwOrder::STATUS_APPROVED,
                SwOrder::STATUS_PROCESSING,
                SwOrder::STATUS_OUTFORDELIVERY,
                SwOrder::STATUS_DELIVERED,
                SwOrder::STATUS_CANCELED,
            ]),
        ]);

        $order->order_status = $validatedData['order_status'];
        $order->save();

        return response()->json([
            'message' => 'Order status updated successfully.',
        ]);
    }


    public function update(SwOrder $order, Request $request) {}

    public function delete() {}
}
