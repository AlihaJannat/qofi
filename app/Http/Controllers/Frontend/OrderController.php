<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Http\Utils\OrderUtils;
use App\Models\SwOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public $orderUtils;

    public function __construct(OrderUtils $orderUtils)
    {
        $this->orderUtils = $orderUtils;
    }

    public function index(Request $request)
    {

        $cart = [
            'bertha-hamilton' => [
                'e69ce6ef-f7b6-4eb5-bc9b-7b05c3b9633e' => [
                    'product_id' => '44',
                    'quantity' => '1',
                    'date' => '2024-08-25',
                    'time' => '04:00:00',
                    'personal_message' => null,
                    'name' => 'Coffee americano',
                    'price' => 66.24,
                    'image_name' => '/products/1723551258985_oFCjlaSEMzOvlb5KzfmVFAvCok67SwCDBG0oskCEdVTiU.PNG',
                    'slug' => 'sawyer-frost',
                    'shop_slug' => 'bertha-hamilton',
                    'shop' => [
                        'id' => 1,
                        'name' => 'Bertha Hamilton',
                        'slug' => 'bertha-hamilton',
                        'delivery_type' => 'free_delivery',
                        'amount_limit' => 0,
                        'delivery_charges' => 0,
                    ]
                ]
            ],
            'kai-hendrix' => [
                '02bd6110-f655-4451-b275-0a075c1da8a6' => [
                    'product_id' => '49',
                    'quantity' => '1',
                    'date' => '2024-08-25',
                    'time' => '01:30:00',
                    'personal_message' => null,
                    'name' => 'Latte',
                    'price' => 56.1,
                    'image_name' => '/products/17229319291_yyaclRu0Nh4TqCSbRKkSWCLQ2Bme11eoBZAqFLkj61Lvd.png',
                    'slug' => 'vivien-graham',
                    'shop_slug' => 'kai-hendrix',
                    'shop' => [
                        'id' => 2,
                        'name' => 'Kai Hendrix',
                        'slug' => 'kai-hendrix',
                        'delivery_type' => 'over_amount',
                        'amount_limit' => 100,
                        'delivery_charges' => 5,
                    ]
                ],
                '3adeafc7-0399-4ee3-a3b2-19b5d91c87b6' => [
                    'product_id' => '50',
                    'quantity' => '1',
                    'date' => '2024-08-25',
                    'time' => '01:30:00',
                    'personal_message' => null,
                    'name' => 'French Vanilla',
                    'price' => 56.1,
                    'image_name' => '/products/1723715487623_ZgvNSUyRxYl30phWKHZMpG5iTcdpOgJcqFikGu9h4TUpG.PNG',
                    'slug' => 'vivien-graham',
                    'shop_slug' => 'kai-hendrix',
                    'shop' => [
                        'id' => 2,
                        'name' => 'Kai Hendrix',
                        'slug' => 'kai-hendrix',
                        'delivery_type' => 'over_amount',
                        'amount_limit' => 200,
                        'delivery_charges' => 5,
                        // 'delivery_type' => 'flat_charges',
                        // 'amount_limit' => 0,
                        // 'delivery_charges' => 10,
                    ]
                ]
            ]
        ];

        if ($request->isMethod('post')) {
            // dd('hi there');
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:20',
                'address' => 'required|string|max:1000',
                'country' => 'required|string|max:100',
                'state' => 'required|string|max:100',
                'city' => 'required|string|max:100',
                'postal_code' => 'required|string|max:10',
                'payment_channel' => 'required|string|max:10',
            ]);

            $dateTime = getCurrentDateTimeInfo();
            $charges = calculateCharges($cart);
            //order table content
            $validated['amount'] = $charges['total_amount'];
            $validated['vat_amount'] = $charges['total_vat_amount'];
            $validated['service_charges'] = $charges['total_delivery_charges'];
            $validated['coupon_code'] = null;
            $validated['discount_amount'] = 0;
            $validated['sub_total'] = $charges['sub_total'];
            $validated['is_completed'] = 0;
            $validated['delivery_day'] = $dateTime['day'];
            $validated['delivery_date'] = $dateTime['date'];
            $validated['delivery_time'] = $dateTime['time'];
            $validated['order_status'] = SwOrder::STATUS_PENDING;
            $validated['status'] = 1;
            //order payment content
            $validated['currency'] = app_setting('site_currency');
            $validated['charge_id'] = null;
            //order products
            $validated['products'] = getCartProducts($cart);

            // dd($validated);
            $order = $this->orderUtils->createOrder($validated);

            if ($order) {
                dd('order placed', $order);
            }
        } else {
            // dd(getCartProducts($cart));
            // dd(Auth::user());
            return view('frontend.checkout.form', compact('cart'));
        }
    }
}
