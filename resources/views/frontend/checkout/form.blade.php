@extends('layout.frontapp')
@section('title')
Checkout
@endsection
@section('content')

<style>
    .containerform {
        max-width: 800px;
        margin: 20px auto;
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    h2 {
        text-align: center;
        color: #333;
    }

    form {
        margin-top: 20px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    label {
        display: block;
        font-weight: bold;
        margin-bottom: 5px;
    }

    input,
    select {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .btn-submit {
        width: 100%;
        padding: 15px;
        background-color: #28a745;
        border: none;
        color: white;
        font-size: 16px;
        cursor: pointer;
        border-radius: 4px;
    }

    .btn-submit:hover {
        background-color: #218838;
    }
</style>

@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
<div class="container containerform">
    <h2>Checkout</h2>

    <!-- Cart Summary -->
    <div class="cart-summary">
        <h2>Your Cart</h2>

        @php
        $total = 0;
        $grandTotal = 0;
        @endphp

        @foreach ($cart as $shopSlug => $products)
        <h4>Shop: {{ $products[array_key_first($products)]['shop']['name'] }}</h4>
        @php
        $shopTotal = 0;
        $deliveryCharge = 0;
        $shopDeliveryType = $products[array_key_first($products)]['shop']['delivery_type'];
        $amountLimit = $products[array_key_first($products)]['shop']['amount_limit'];
        $shopDeliveryCharges = $products[array_key_first($products)]['shop']['delivery_charges'];
        @endphp

        @foreach ($products as $product)
        <div class="cart-item">
            <img src="{{ asset('images/'.$product['image_name']) }}" alt="{{ $product['name'] }}" width="40">
            <div class="cart-details">
                <h4>{{ $product['name'] }}</h4>
                <p>Quantity: {{ $product['quantity'] }}</p>
                <p>Price: ${{ number_format($product['price'], 2) }}</p>
            </div>
        </div>

        @php
        $productTotal = $product['price'] * $product['quantity'];
        $shopTotal += $productTotal;
        $total += $productTotal;
        @endphp
        @endforeach

        <!-- Calculate Delivery Charges Based on Delivery Type -->
        @php
        if ($shopDeliveryType == 'flat_charges') {
        $deliveryCharge = $shopDeliveryCharges;
        } elseif ($shopDeliveryType == 'over_amount' && $shopTotal < $amountLimit) {
            $deliveryCharge=$shopDeliveryCharges; } @endphp <div class="shop-total">
            <p>Shop Total: ${{ number_format($shopTotal, 2) }}</p>
            <p>Delivery Charge: ${{ number_format($deliveryCharge, 2) }}</p>
    </div>

    @php
    $grandTotal += $shopTotal + $deliveryCharge;
    @endphp
    @endforeach

    <!-- Grand Total -->
    <div class="grand-total">
        <p>Total Products: ${{ number_format($total, 2) }}</p>
        <p><strong>Grand Total (including delivery charges): ${{ number_format($grandTotal, 2) }}</strong></p>
    </div>
</div>

<form action="/checkout" method="post">
    @csrf
    <!-- Laravel CSRF Protection -->
    <!-- Billing Details -->
    <div class="form-group">
        <label for="name">Name</label>
        <input type="text" id="name" name="name" required
            value="{{Auth::user()->first_name." ".Auth::user()->last_name }}">
    </div>
    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required value="{{Auth::user()->email}}">
    </div>
    <div class="form-group">
        <label for="phone">Phone</label>
        <input type="text" id="phone" name="phone" required value="{{Auth::user()->phone}}">
    </div>

    <!-- Shipping Details -->
    <div class="form-group">
        <label for="address">Address</label>
        <input type="text" id="address" name="address" required>
    </div>
    <div class="form-group">
        <label for="city">City</label>
        <input type="text" id="city" name="city" required>
    </div>
    <div class="form-group">
        <label for="state">State</label>
        <input type="text" id="state" name="state" required>
    </div>
    <div class="form-group">
        <label for="country">Country</label>
        <input type="text" id="country" name="country" required>
    </div>
    <div class="form-group">
        <label for="zip">Postal Code</label>
        <input type="text" id="postal_code" name="postal_code" required>
    </div>

    <!-- Payment Method -->
    <div class="form-group">
        <label for="payment-method">Payment Method</label>
        <select id="payment-method" name="payment_channel" required>
            <option value="cod">COD</option>
            <option value="credit_card">Credit Card</option>
            <option value="paypal">PayPal</option>
            <option value="bank_transfer">Bank Transfer</option>
        </select>
    </div>

    <!-- Submit Button -->
    <button type="submit" class="btn-submit">Complete Order</button>
</form>
</div>
@endsection