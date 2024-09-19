@extends('layout.adminapp')
@section('content')
<style>
    .productlist {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
    }

    .productleft {
        display: flex;
        align-items: baseline;
    }

    .shop {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
        padding: 10px;
        background: #edf4f6;
        border-radius: 5px;
    }

    .productleft p,
    .shop h6 {
        margin: 10px
    }

    .shoplogo {
        border-radius: 50%;
    }

    .shopproducts {
        margin-left: 20px;
    }

    .total {
        float: right;
        margin-right: 10px;
    }

    .total h6 {
        font-weight: 400 !important;
    }

    .customerdetail {
        background: #d9dcdd;
    }

    .switch {
        position: relative;
        display: inline-block;
        width: 40px;
        height: 14px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .4s;
        border-radius: 34px;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 14px;
        width: 14px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }

    input:checked+.slider {
        background-color: #28a745;
    }

    input:checked+.slider:before {
        transform: translateX(20px);
    }

    .add-attribute {
        display: flex;
        justify-content: end;
        align-items: center;
    }

    .add-attribute-2 {
        display: flex;
        align-items: center;
    }

    .main-attribute-add {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    img.image_order_main {
        border-radius: 50%;
        width: 150px;
        height: 150px;
        object-fit: cover;
        cursor: pointer;
    }

    img.image_order {
        border-radius: 50%;
        width: 120px;
        height: 120px;
        object-fit: cover;
        cursor: pointer;
    }

    img.image_order:hover,
    img.image_order_main:hover {
        -webkit-filter: brightness(70%);
        -webkit-transition: all 1s ease;
        -moz-transition: all 1s ease;
        -o-transition: all 1s ease;
        -ms-transition: all 1s ease;
        transition: all 1s ease;
    }

    .img_wrap {
        position: relative;
        text-align: left;
    }

    .select2-selection__choice,
    .select2-results__option {
        display: flex;
        align-items: center
    }
</style>
@php
$currency = app_setting('site_currency');

@endphp
<div class="container mx-auto p-6">
    <!-- Order Details Header -->
    <div class="row  shadow-md rounded-lg  mb-6">
        <h2 class=" text-xl font-semibold mb-4">Order Details</h2>
        <div class="col col-sm-8 bg-white p-4 ">
            <!-- Shops and Products List -->

            @foreach ($order->product as $shopName => $details)
            <div class="shop">
                <img class="shoplogo" src="{{asset('images/'.$details['shop_details']['image_name'])}}" width="40" />
                <h6 class="">{{ $shopName }}</h6>
            </div>

            <div class="shopproducts">
                <ul class="list-unstyled">
                    @if (!empty($details['order_shop_products']))
                    @foreach ($details['order_shop_products'] as $productDetail)
                    <li>
                        <div class="productlist">
                            <div class="productleft">
                                <img src="{{asset('images/'.$productDetail['product']['image_name'])}}" width="60" />
                                <p>
                                    <a href="">{{ $productDetail['product']['name'] }}</a>
                                </p>
                            </div>

                            <div class="productright">
                                <p>{{$currency}}{{ number_format($productDetail['product']['price'],
                                    2) }}
                                    x {{ $productDetail['quantity'] }}</p>
                            </div>

                        </div>
                    </li>
                    @endforeach
                    @else
                    <p>No products found for this shop.</p>
                    @endif
                </ul>

            </div>
            @endforeach
            <hr>
            <!-- Total Price -->
            <div class="total">
                <h6>Delivery Charges: {{$currency}} {{ $order->service_charges }}</h6>
                <h6>Vat Amount: {{$currency}} {{ $order->vat_amount }}</h6>
                <h6>Sub Total:{{$currency}} {{ $order->sub_total }}</h6>
                <h6><strong>Total Amount: {{$currency}} {{ $order->total_amount }}</strong></h6>
            </div>
        </div>
        <div class="col-sm-4  p-4 customerdetail">
            <!-- Order Status -->
            <div class="bg-white p-4 rounded-lg">
                <form id="status-form">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <select name="order_status" class="form-control">
                            <option value="{{ \App\Models\SwOrder::STATUS_PENDING }}" {{ $order->order_status ==
                                \App\Models\SwOrder::STATUS_PENDING ? 'selected' : '' }}>Pending</option>
                            <option value="{{ \App\Models\SwOrder::STATUS_APPROVED }}" {{ $order->order_status ==
                                \App\Models\SwOrder::STATUS_APPROVED ? 'selected' : '' }}>Approved</option>
                            <option value="{{ \App\Models\SwOrder::STATUS_PROCESSING }}" {{ $order->order_status ==
                                \App\Models\SwOrder::STATUS_PROCESSING ? 'selected' : '' }}>Processing</option>
                            <option value="{{ \App\Models\SwOrder::STATUS_OUTFORDELIVERY }}" {{ $order->order_status ==
                                \App\Models\SwOrder::STATUS_OUTFORDELIVERY ? 'selected' : '' }}>Out For Delivery
                            </option>
                            <option value="{{ \App\Models\SwOrder::STATUS_DELIVERED }}" {{ $order->order_status ==
                                \App\Models\SwOrder::STATUS_DELIVERED ? 'selected' : '' }}>Delivered</option>
                            <option value="{{ \App\Models\SwOrder::STATUS_CANCELED }}" {{ $order->order_status ==
                                \App\Models\SwOrder::STATUS_CANCELED ? 'selected' : '' }}>Canceled</option>
                        </select>
                    </div>

                    <div id="status-message" class="mt-3"></div>
                    <button type="submit" class="btn btn-primary mt-2">
                        Update Status
                        <img id="loader" src="{{asset('admindic/img/icons/loader-white.svg')}}" class="px-1"
                            style="display: none;">
                    </button>
                </form>
            </div>
            <!-- Customer Details -->
            <div class="bg-white p-4 rounded-lg mt-3">
                <h3 class="text-lg font-medium mb-3 ">Customer Details</h3>
                <p class="mb-0"><strong>Name:</strong> {{ $address->name }}</p>
                <p class="mb-0"><strong>Email:</strong> {{ $address->email }}</p>
                <p class="mb-0"><strong>Phone:</strong> {{ $address->phone }}</p>
                <p class="mb-0"><strong>Address:</strong> {{ $address->getFullAddress() }}</p>
            </div>
            <!-- payment Details -->
            <div class="bg-white p-4 rounded-lg mt-3">
                <h3 class="text-lg font-medium mb-3 ">Payment Details
                    <span
                        class="uppercase bold right  {{ $order->payment->payment_status == $order->payment::STATUS_PAID ? 'text-success' : 'text-danger' }}">
                        {{$order->payment->payment_status}}</span>
                </h3>
                <p class="mb-0"><strong>Method:</strong> {{ $order->payment->paymentMethod() }}</p>
                <p class="mb-0"><strong>Amount:</strong> {{ $order->payment->currency }} {{ $order->payment->amount }}
                </p>
            </div>
        </div>

    </div>


</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        $('#status-form').on('submit', function(e) {
            e.preventDefault(); // Prevent the form from submitting the default way

            var formData = $(this).serialize();
            var url = "{{ route('admin.order.updateStatus', $order->id) }}"; // Update with your route
            $('#loader').show();

            $.ajax({
                url: url,
                type: 'PUT',
                data: formData,
                success: function(response) {
                    $('#loader').hide();
                    $('#status-message').html('<div class="text-success">' + response.message + '</div>');
                    setTimeout(function() {
                        $('#status-message').fadeOut('slow');
                    }, 3000); // Hide the message after 3 seconds
                },
                error: function(xhr) {
                    $('#loader').hide();
                    $('#status-message').html('<div class="text-danger">Error: ' + xhr.responseJSON.message + '</div>');
                    setTimeout(function() {
                        $('#status-message').fadeOut('slow');
                    }, 3000); // Hide the message after 3 seconds
                }
            });
        });
    });
</script>
@endsection