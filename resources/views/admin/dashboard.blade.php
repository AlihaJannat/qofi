@extends('layout.adminapp')
@section('content')
    <!-- Begin Page Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <!-- Page Heading -->
            <h1 class="h3 mb-4 text-gray-800">Hello {{ auth()->guard('admin')->user()->name ?? 'no one' }}! Welcome to
                Dashboard</h1>

            @if (count($topShops))
                @php
                    $topShop = $topShops[0];
                @endphp
                <div class="col-lg-4 col-md-6 col-12 mb-4">
                    <div class="card h-100">
                        <div class="card-header">
                            <h3 class="card-title mb-2">{{ $topShop->name }}</h3>
                            <span class="d-block mb-4 text-nowrap">Best seller of the month ({{date('M')}})</span>
                        </div>
                        <div class="card-body">
                            <div class="row align-items-end">
                                <div class="col-6">
                                    <h1 class="display-6 text-primary mb-2 pt-4 pb-1"><small>KWD</small>
                                        {{-- {{ number_format($topShop->total_sales) }} --}}
                                        100
                                    </h1>
                                    <small class="d-block mb-3">Have done 
                                        {{-- {{ $percentageChange }} --}}
                                        20
                                        % 
                                        <br>more sales
                                        today.</small>

                                    {{-- <a href="javascript:;" class="btn btn-sm btn-primary">View sales</a> --}}
                                </div>
                                <div class="col-6">
                                    <img src="{{ asset('admindic/img/illustrations/prize-light.png') }}" width="140"
                                        height="150" class="rounded-start" alt="View Sales"
                                        data-app-light-img="illustrations/prize-light.png"
                                        data-app-dark-img="illustrations/prize-light.png">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="col-md-6 col-lg-6 col-xl-4 col-xl-4 mb-4">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between mb-3">
                        <h5 class="card-title mb-0">Top Selling Shop ({{date('M')}})</h5>
                    </div>
                    <div class="card-body">
                        <ul class="p-0 m-0">
                            @foreach ($topShops as $topShop)
                                <li class="d-flex mb-4">
                                    <div class="avatar avatar-sm flex-shrink-0 me-3">
                                        <span class="avatar-initial rounded-circle bg-label-primary">
                                            <img src="{{ filter_var($topShop->image_name, FILTER_VALIDATE_URL) ? $topShop->image_name : asset('images' . $topShop->image_name) }}"
                                                alt="">
                                        </span>
                                    </div>
                                    <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                        <div class="me-2">
                                            <p class="mb-0 lh-1">{{ $topShop->name }}</p>
                                            {{-- <small class="text-muted">KWD {{ number_format($topShop->total_sales,2) }} Sales</small> --}}
                                            <small class="text-muted">KWD 10 Sales</small>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Multi Radial Chart -->
            <div class="col-lg-4 col-md-6 col-12 mb-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Order Stats of {{ date('Y') }}</h5>

                    </div>
                    <div class="card-body">
                        <input type="hidden" id="shopOrderCount" value="{{ 10}}">
                        <input type="hidden" id="siteOrderCount" value="{{ 20 }}">
                        <div id="impressionDonutChart"></div>
                    </div>
                </div>
            </div>

            {{--  --}}

            <!-- Order Summary -->
            <div class="col-xl-6 col-12 mb-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Order Summary</h5>
                    </div>
                    <div class="card-body p-0">
                        <div id="orderSummaryChart"></div>
                    </div>
                </div>
            </div>
            <!--/ Order Summary -->
            <div class="col-md-6 col-lg-6 col-xl-4 mb-4 mb-xl-0">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title mb-2">All Users</h5>
                        <h1 class="display-6 fw-normal mb-0">{{ number_format($userStats['totalUsers'], 0) }}</h1>
                    </div>
                    <div class="card-body">
                        <span class="d-block mb-2">Current Activity</span>
                        <div class="progress progress-stacked mb-3 mb-xl-5" style="height:8px;">
                            <div class="progress-bar bg-success" role="progressbar"
                                style="width: {{ $userStats['customerPercentage'] }}%"
                                aria-valuenow="{{ $userStats['customerPercentage'] }}" aria-valuemin="0"
                                aria-valuemax="100"></div>
                            {{-- <div class="progress-bar bg-danger" role="progressbar"
                                style="width: {{ $userStats['studentPercentage'] }}%"
                                aria-valuenow="{{ $userStats['studentPercentage'] }}" aria-valuemin="0"
                                aria-valuemax="100"></div> --}}
                            <div class="progress-bar bg-info" role="progressbar"
                                style="width: {{ $userStats['adminPercentage'] }}%"
                                aria-valuenow="{{ $userStats['adminPercentage'] }}" aria-valuemin="0"
                                aria-valuemax="100"></div>
                            <div class="progress-bar bg-warning" role="progressbar"
                                style="width: {{ $userStats['vendorPercentage'] }}%"
                                aria-valuenow="{{ $userStats['vendorPercentage'] }}" aria-valuemin="0"
                                aria-valuemax="100"></div>
                        </div>
                        <ul class="p-0 m-0">
                            <li class="mb-3 d-flex justify-content-between">
                                <div class="d-flex align-items-center lh-1 me-3">
                                    <span class="badge badge-dot bg-success me-2"></span> Customers
                                </div>
                                <div class="d-flex gap-3">
                                    <span>{{ $userStats['customerCount'] }}</span>
                                    <span
                                        class="fw-semibold">{{ number_format($userStats['customerPercentage'], 2) }}%</span>
                                </div>
                            </li>
                            {{-- <li class="mb-3 d-flex justify-content-between">
                                <div class="d-flex align-items-center lh-1 me-3">
                                    <span class="badge badge-dot bg-danger me-2"></span> Students
                                </div>
                                <div class="d-flex gap-3">
                                    <span>{{ $userStats['studentCount'] }}</span>
                                    <span
                                        class="fw-semibold">{{ number_format($userStats['studentPercentage'], 2) }}%</span>
                                </div>
                            </li> --}}
                            <li class="mb-3 d-flex justify-content-between">
                                <div class="d-flex align-items-center lh-1 me-3">
                                    <span class="badge badge-dot bg-info me-2"></span> Admin
                                </div>
                                <div class="d-flex gap-3">
                                    <span>{{ $userStats['adminCount'] }}</span>
                                    <span
                                        class="fw-semibold">{{ number_format($userStats['adminPercentage'], 2) }}%</span>
                                </div>
                            </li>
                            <li class="mb-3 d-flex justify-content-between">
                                <div class="d-flex align-items-center lh-1 me-3">
                                    <span class="badge badge-dot bg-warning me-2"></span> Vendor
                                </div>
                                <div class="d-flex gap-3">
                                    <span>{{ $userStats['vendorCount'] }}</span>
                                    <span
                                        class="fw-semibold">{{ number_format($userStats['vendorPercentage'], 2) }}%</span>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-12">
                <div class="row">
                    <!-- Statistics Cards -->
                    <div class="col-12 mb-4">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <div class="avatar mx-auto mb-2">
                                    <span class="avatar-initial rounded-circle bg-label-danger"><i
                                            class="bx bx-purchase-tag fs-4"></i></span>
                                </div>
                                <span class="d-block text-nowrap">Shop Order</span>
                                {{-- <h2 class="mb-0">{{ $orderStats['shopOrdersCount'] }}</h2> --}}
                                <h2 class="mb-0">10</h2>
                                <span class="d-block text-nowrap">KWD
                                    {{-- {{ number_format($orderStats['shopOrdersSum'], 2) }} --}}
                                    23.40
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mb-4">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <div class="avatar mx-auto mb-2">
                                    <span class="avatar-initial rounded-circle bg-label-success"><i
                                            class="bx bx-cart fs-4"></i></span>
                                </div>
                                <span class="d-block text-nowrap">App Order</span>
                                {{-- <h2 class="mb-0">{{ $orderStats['siteOrdersCount'] }}</h2> --}}
                                <h2 class="mb-0">15</h2>
                                <span class="d-block text-nowrap">KWD
                                    243.3
                                    {{-- {{ number_format($orderStats['siteOrdersSum'], 2) }} --}}
                                </span>
                            </div>
                        </div>
                    </div>
                    <!--/ Statistics Cards -->
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    {{-- <script>
        let saleAmount = [];
        let saleMonth = [];
        let maxSale = 0
        @foreach ($currentYearSale as $key => $sale)
            saleAmount.push({{ $sale->sum }});
            saleMonth.push("{{ $sale->name }}");
            if (saleAmount[saleAmount.length - 1] > maxSale) {
                maxSale = parseInt(saleAmount[saleAmount.length - 1] + (saleAmount[saleAmount.length - 1] / 5))
            }
        @endforeach
    </script> --}}
    <script src="{{ asset('admindic/js/dashboards-ecommerce.js') }}"></script>
@endsection
