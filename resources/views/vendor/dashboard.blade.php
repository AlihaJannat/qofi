@extends('layout.vendorapp')
@section('content')
    <!-- Begin Page Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">

            <!-- Page Heading -->
            <h1 class="h3 mb-4 text-gray-800">Hello {{ auth()->guard('vendor')->user()->name ?? 'no one' }}! Welcome to
                Dashboard</h1>

            @if (count($topItems))
                @php
                    $topItem = $topItems[0];
                @endphp
                <div class="col-lg-4 col-md-6 col-12 mb-4">
                    <div class="card h-100">
                        <div class="card-header">
                            <h3 class="card-title mb-2">{{ $topItem->name }}</h3>
                            <span class="d-block mb-4 text-nowrap">Best selling item of the month
                                ({{ date('M') }})</span>
                        </div>
                        <div class="card-body">
                            <div class="row align-items-end">
                                <div class="col-6">
                                    <h1 class="text-primary mb-2 pt-4 pb-1"><small>KWD</small>
                                        {{-- {{ number_format($topItem->items_sold * $topItem->price) }}</h1> --}}
                                        234</h1>
                                    <small class="d-block mb-3">Total of <strong>
                                        {{-- {{ $topItem->items_sold }} --}}
                                        423
                                    </strong> items
                                        <br>sold this month.</small>

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
                        <h5 class="card-title mb-0">Top Selling Items ({{ date('M') }})</h5>
                    </div>
                    <div class="card-body">
                        <ul class="p-0 m-0">
                            @foreach ($topItems as $topItem)
                                <li class="d-flex mb-4">
                                    <div class="avatar avatar-sm flex-shrink-0 me-3">
                                        <span class="avatar-initial rounded-circle bg-label-info"><i
                                                class="bx bx-pie-chart-alt"></i></span>
                                    </div>
                                    <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                        <div class="me-2">
                                            <p class="mb-0 lh-1">{{ $topItem->name }}</p>
                                            <small class="text-muted">{{ number_format($topItem->items_sold, 2) }}
                                                Item Sold</small>
                                        </div>
                                        <div class="item-progress">KWD
                                            {{-- {{ number_format($topItem->items_sold * $topItem->price, 2) }}</div> --}}
                                            {{ number_format(23 * 12, 2) }}</div>
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
                        <input type="hidden" id="shopOrderCount" value="10">
                        <input type="hidden" id="siteOrderCount" value="15">
                        <div id="impressionDonutChart"></div>
                    </div>
                </div>
            </div>

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
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-2">Total Users</h5>
                        
                        <h1 class="display-6 fw-normal mb-0">{{ number_format($userStats->sum('role_count'), 0) }}</h1>
                    </div>
                    <div class="card-body">
                        <ul class="p-0 m-0">
                            @foreach ($userStats as $userStat)
                                
                            <li class="mb-3 d-flex justify-content-between">
                                <div class="d-flex align-items-center lh-1 me-3">
                                    {{$userStat->role}}
                                </div>
                                <div class="d-flex gap-3">
                                    <span>{{ $userStat->role_count }}</span>
                                    @php
                                        $percentage = ($userStat->role_count/$userStats->sum('role_count'))*100;
                                    @endphp
                                    <span
                                        class="fw-semibold">{{ number_format($percentage, 2) }}%</span>
                                </div>
                            </li>
                            @endforeach
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
                                <h2 class="mb-0">12</h2>
                                <span class="d-block text-nowrap">KWD
                                    {{-- {{ number_format($orderStats['shopOrdersSum'], 2) }} --}}
                                2344.23
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
                                <h2 class="mb-0">12</h2>
                                {{-- <h2 class="mb-0">{{ $orderStats['siteOrdersCount'] }}</h2> --}}
                                <span class="d-block text-nowrap">KWD
                                    {{-- {{ number_format($orderStats['siteOrdersSum'], 2) }} --}}
                                768.2
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
