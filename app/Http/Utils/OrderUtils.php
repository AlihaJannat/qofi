<?php

namespace App\Http\Utils;

use App\Models\SwOrder;
use App\Models\SwOrderAddress;
use App\Models\SwOrderPayment;
use App\Models\SwOrderProduct;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderUtils
{

    public function createOrder(array $validateOrder)
    {
        DB::beginTransaction();

        try {
            // Create Order
            $order = new SwOrder();
            $order->user_id = Auth::user()->id;
            $order->sw_order_payment_id = null;
            $order->sw_order_address_id = null;
            $order->amount = $validateOrder['amount'];
            $order->vat_amount = $validateOrder['vat_amount'];
            $order->service_charges = $validateOrder['service_charges'];
            $order->coupon_code = $validateOrder['coupon_code'];
            $order->discount_amount = $validateOrder['discount_amount'];
            $order->sub_total = $validateOrder['sub_total'];
            $order->delivery_day = $validateOrder['delivery_day'];
            $order->delivery_time = $validateOrder['delivery_time'];
            $order->delivery_date = $validateOrder['delivery_date'];
            $order->is_completed = false;
            $order->order_status = SwOrder::STATUS_PENDING;
            $order->status = true;
            $order->save();

            // Create Order Products
            foreach ($validateOrder['products'] as $productData) {
                $orderProduct = new SwOrderProduct();
                $orderProduct->sw_order_id = $order->id;
                $orderProduct->sw_product_id = $productData['product_id'];
                $orderProduct->product_name = $productData['product_name'];
                $orderProduct->quantity = $productData['quantity'];
                $orderProduct->price = $productData['price'];
                $orderProduct->options = json_encode($productData['options']);
                $orderProduct->save();
            }

            // Create Order Address
            $orderAddress = new SwOrderAddress();
            $orderAddress->sw_order_id = $order->id;
            $orderAddress->name = $validateOrder['name'];
            $orderAddress->email = $validateOrder['email'];
            $orderAddress->phone = $validateOrder['phone'];
            $orderAddress->country = $validateOrder['country'];
            $orderAddress->state = $validateOrder['state'];
            $orderAddress->city = $validateOrder['city'];
            $orderAddress->address = $validateOrder['address'];
            $orderAddress->status = true;
            $orderAddress->postal_code = $validateOrder['postal_code'];
            $orderAddress->save();

            // Update order with address ID
            $order->sw_order_address_id = $orderAddress->id;
            $order->save();

            // Create Order Payment
            $orderPayment = new SwOrderPayment();
            $orderPayment->user_id = Auth::user()->id;
            $orderPayment->sw_order_id = $order->id;
            $orderPayment->currency = app_setting('site_currency');
            $orderPayment->charge_id = NULL;
            $orderPayment->payment_channel = $validateOrder['payment_channel'];
            $orderPayment->amount = $validateOrder['amount'];
            $orderPayment->refund_amount = 0;
            $orderPayment->refund_note = null;
            $orderPayment->status = 1;
            $orderPayment->save();

            // Update order with payment ID
            $order->sw_order_payment_id = $orderPayment->id;
            $order->save();

            DB::commit();

            return $order;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }
}
