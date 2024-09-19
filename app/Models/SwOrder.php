<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SwOrder extends Model
{
    use HasFactory;

    const STATUS_PENDING = 'Pending';
    const STATUS_APPROVED = 'Approved';
    const STATUS_PROCESSING = 'Processing';
    const STATUS_OUTFORDELIVERY = 'Out For Delivery';
    const STATUS_DELIVERED = 'Delivered';
    const STATUS_CANCELED = 'Canceled';

    protected $fillable = [
        'user_id',
        'sw_order_payment_id',
        'sw_order_address_id',
        'amount',
        'vat_amount',
        'service_charges',
        'coupon_code',
        'discount_amount',
        'sub_total',
        'delivery_day',
        'delivery_time',
        'delivery_date',
        'is_completed',
        'order_status',
        'status',
    ];



    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function orderProducts()
    {
        return $this->hasMany(SwOrderProduct::class);
    }

    public function payment()
    {
        return $this->belongsTo(SwOrderPayment::class, 'sw_order_payment_id');
    }

    public function address()
    {
        return $this->belongsTo(SwOrderAddress::class, 'sw_order_address_id');
    }

    public function getShopsWithOrderDetails()
    {
        $shopsWithOrderDetails = [];

        // Get all order products with their related product and shop details
        $orderProducts = SwOrderProduct::with(['product.shop'])
            ->where('sw_order_id', $this->id)
            ->get();

        // Group the order products by shop
        $shops = $orderProducts->groupBy(function ($orderProduct) {
            return $orderProduct->product->sw_shop_id; // Group by shop ID
        });

        // Iterate through each shop and structure the response
        foreach ($shops as $shopId => $products) {
            if ($products->isNotEmpty()) {
                $shop = $products->first()->product->shop; // Get the shop details

                $shopsWithOrderDetails[$shop->name] = [
                    'shop_details' => $shop,
                    'order_shop_products' => $products->map(function ($orderProduct) {
                        return [
                            'product' => $orderProduct->product,
                            'quantity' => $orderProduct->quantity,
                            'price' => $orderProduct->price,
                            'options' => $orderProduct->options,
                        ];
                    })->toArray()
                ];
            }
        }

        return $shopsWithOrderDetails;
    }

    public function orderProductsShops()
    {
        $orderProducts = SwOrderProduct::with(['product.shop'])
            ->where('sw_order_id', $this->id)
            ->get();

        $shops = [];

        // Iterate through each order product to retrieve shop details
        foreach ($orderProducts as $orderProduct) {
            if ($orderProduct->product && $orderProduct->product->shop) {
                $shop = $orderProduct->product->shop;
                $shops[$shop->id] = $shop; // Store shop details by shop ID to avoid duplicates
            }
        }

        return array_values($shops);
    }





    //some functions
    public function isApproved()
    {
        return $this->order_status === self::STATUS_APPROVED;
    }
    public function isDelivered()
    {
        return $this->order_status === self::STATUS_DELIVERED;
    }

    //vendor order
    public static function vendorOrders($vendor)
    {
        $vendor = SwVendor::findorFail($vendor);
        $shopIds = SwShop::where('owner_id', $vendor->id)->pluck('id'); // Get all shop IDs associated with the vendor
        // Fetch orders related to those shop's products

        $orders = SwOrder::whereHas('orderProducts.product', function ($query) use ($shopIds) {
            $query->whereIn('sw_shop_id', $shopIds);
        })->get();

        foreach ($orders as $order) {
            $order->vendor_sub_total = $order->orderProducts->whereIn('product.sw_shop_id', $shopIds)->sum(function ($orderProduct) {
                return $orderProduct->quantity * $orderProduct->price;
            });
        }

        $deliveryCharge = $vendor->shop->deliverycharges;
        $deliveryCharges = 0;
        if ($deliveryCharge->delivery_charge_type == 'flat_charges') {
            $deliveryCharges = $deliveryCharge->max_amount;
        } elseif ($deliveryCharge->delivery_charge_type == 'over_amount' && $deliveryCharge->limit > $order->vendor_sub_total) {
            $deliveryCharges = $deliveryCharge->max_amount;
        }

        $order->delivery_type = $deliveryCharge->delivery_charge_type;
        $order->delivery_charges = $deliveryCharges;
        $order->vendor_total = $deliveryCharges + $order->vendor_sub_total;

        return $orders;
    }

    public static function vendorSingleOrder($vendor, $orderId)
    {
        $vendor = SwVendor::findorFail($vendor);
        $shopIds = SwShop::where('owner_id', $vendor->id)->pluck('id'); // Get all shop IDs associated with the vendor
        // Fetch orders related to those shop's products
        $order = SwOrder::whereHas('orderProducts.product', function ($query) use ($shopIds) {
            $query->whereIn('sw_shop_id', $shopIds);
        })->where('id', $orderId)->first();

        $order->vendor_sub_total = $order->orderProducts->whereIn('product.sw_shop_id', $shopIds)->sum(function ($orderProduct) {
            return $orderProduct->quantity * $orderProduct->price;
        });

        $deliveryCharge = $vendor->shop->deliverycharges;
        $deliveryCharges = 0;
        if ($deliveryCharge->delivery_charge_type == 'flat_charges') {
            $deliveryCharges = $deliveryCharge->max_amount;
        } elseif ($deliveryCharge->delivery_charge_type == 'over_amount' && $deliveryCharge->limit > $order->vendor_sub_total) {
            $deliveryCharges = $deliveryCharge->max_amount;
        }


        $order->product = $order->orderProducts->whereIn('product.sw_shop_id', $shopIds);
        $order->delivery_type = $deliveryCharge->delivery_charge_type;
        $order->delivery_charges = $deliveryCharges;
        $order->vendor_total = $deliveryCharges + $order->vendor_sub_total;
        return $order;
    }
}
