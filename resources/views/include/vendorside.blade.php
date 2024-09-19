@php
$vendor = auth('vendor')->user();
$isOwner = $vendor->isOwner();
$roleRel = $vendor->roleRel;
@endphp
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo ">
        <a href="{{ route('vendor.dash') }}" class="app-brand-link">
            <span class="app-brand-logo demo">
                <img src="{{ app_setting('site_logo') }}" alt="logo" height="26px" width="26px" class="logo">
            </span>
            <span class="app-brand-text demo menu-text fw-bold ms-2">{{ app_setting('site_name') }}</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="bx menu-toggle-icon d-none d-xl-block fs-4 align-middle"></i>
            <i class="bx bx-x d-block d-xl-none bx-sm align-middle"></i>
        </a>
    </div>


    <div class="menu-divider mt-0  ">
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Dashboards -->
        <li class="menu-item {{ Str::startsWith(Route::currentRouteName(), 'vendor.dash') ? 'active' : '' }}">
            <a href="#" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div data-i18n="Dashboards">Dashboards</div>
            </a>
        </li>
        @if ($isOwner)
        <li class="menu-item {{ Str::startsWith(Route::currentRouteName(), 'vendor.user.') ? 'active' : '' }}">
            <a href="{{ route('vendor.user.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-user"></i>
                <div data-i18n="Shop Users">Shop Users</div>
            </a>
        </li>
        <li class="menu-item {{ Str::startsWith(Route::currentRouteName(), 'vendor.role.') ? 'active' : '' }}">
            <a href="{{ route('vendor.role.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-check-shield"></i>
                <div data-i18n="Roles & Permissions">Roles & Permissions</div>
            </a>
        </li>
        @endif

        {{-- <li class="menu-item {{ Str::startsWith(Route::currentRouteName(), 'banner') ? 'active' : '' }}">
            <a href="#" class="menu-link">
                <i class="menu-icon tf-icons bx bx-image"></i>
                <div data-i18n="Banners">Banners</div>
            </a>
        </li> --}}
        <li class="menu-header small text-uppercase">
            <i class="menu-icon tf-icons bx bx-store"></i>
            <span class="menu-header-text">Shop Management</span>
        </li>

        @php
        $productView = $roleRel?->permissions->contains('name', 'product_view') || $isOwner;
        @endphp

        @if ($productView)
        <li class="menu-item {{ Str::startsWith(Route::currentRouteName(), 'vendor.product.') ? 'active' : '' }}">
            <a href="{{ route('vendor.product.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bxs-florist"></i>
                <div data-i18n="Products">Products</div>
            </a>
        </li>
        @endif

        @php
        $orderView = $roleRel?->permissions->contains('name', 'order_view') || $isOwner;
        @endphp

        @if ($orderView)
        <li class="menu-item {{ Str::startsWith(Route::currentRouteName(), 'vendor.order.') ? 'active' : '' }}">
            <a href="{{ route('vendor.order.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-shopping-bag"></i>
                <div data-i18n="Orders">Orders</div>
            </a>
        </li>
        @endif

        @php
        $calendarView = $roleRel?->permissions->contains('name', 'delivery_calendar') || $isOwner;
        @endphp

        @if ($calendarView)
        <li class="menu-item {{ Str::startsWith(Route::currentRouteName(), 'vendor.calendar.day') ? 'active' : '' }}">
            <a href="{{ route('vendor.calendar.day') }}" class="menu-link">
                <i class="menu-icon tf-icons fa fa-calendar"></i>
                <div data-i18n="Delivery Calendar">Delivery Calendar</div>
            </a>
        </li>
        @endif

        @php
        $couponView = $roleRel?->permissions->contains('name', 'mycoupon_view') || $isOwner;
        @endphp

        @if ($couponView)
        <li class="menu-item {{ Str::startsWith(Route::currentRouteName(), 'vendor.coupon.') ? 'active' : '' }}">
            <a href="{{ route('vendor.coupon.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-gift"></i>
                <div data-i18n="Coupons">Coupons</div>
            </a>
        </li>
        @endif

        @php
        $saleView = $roleRel?->permissions->contains('name', 'sale_view') || $isOwner;
        @endphp
        @if ($saleView)
        <li class="menu-item {{ Str::startsWith(Route::currentRouteName(), 'vendor.sale.') ? 'active' : '' }}">
            <a href="#" class="menu-link">
                <i class="menu-icon tf-icons bx bx-box"></i>
                <div data-i18n="Sales">Sales</div>
            </a>
        </li>
        @endif
        @php
        $reportView = $roleRel?->permissions->contains('name', 'report_view') || $isOwner;
        @endphp
        @if ($reportView)
        <li class="menu-item {{ Str::startsWith(Route::currentRouteName(), 'vendor.report.') ? 'active' : '' }}">
            <a href="#" class="menu-link">
                <i class="menu-icon tf-icons bx bx-file"></i>
                <div data-i18n="Report">Report</div>
            </a>
        </li>
        @endif
        {{-- <li class="menu-item {{ Str::startsWith(Route::currentRouteName(), 'coupon') ? 'active' : '' }}">
            <a href="#" class="menu-link">
                <i class="menu-icon tf-icons bx bx-tag"></i>
                <div data-i18n="Coupons">Coupons</div>
            </a>
        </li>

        <li class="menu-item {{ Str::startsWith(Route::currentRouteName(), 'order') ? 'active' : '' }}">
            <a href="#" class="menu-link">
                <i class="menu-icon tf-icons bx bx-tag"></i>
                <div data-i18n="Orders">Orders</div>
            </a>
        </li>
        <li class="menu-item {{ Str::startsWith(Route::currentRouteName(), 'report') ? 'active' : '' }}">
            <a href="#" class="menu-link">
                <i class="menu-icon tf-icons bx bx-chart"></i>
                <div data-i18n="Reports">Reports</div>
            </a>
        </li> --}}


    </ul>



</aside>