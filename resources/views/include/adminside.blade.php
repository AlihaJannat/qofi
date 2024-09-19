@php
$superAdmin = auth('admin')->user()->id == 1;
@endphp
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo ">
        <a href="{{ route('admin.dash') }}" class="app-brand-link">
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
        <li class="menu-item {{ Str::startsWith(Route::currentRouteName(), 'admin.dash') ? 'active' : '' }}">
            <a href="{{ route('admin.dash') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div data-i18n="Dashboards">Dashboards</div>
            </a>
        </li>
        @php
        $customerView =
        auth('admin')->user()->roleRel?->permissions->contains('name', 'customer_view') || $superAdmin;
        $vendorView = auth('admin')->user()->roleRel?->permissions->contains('name', 'vendor_view') || $superAdmin;
        @endphp
        @if ($superAdmin || $customerView || $vendorView)
        <li
            class="menu-item {{ Str::startsWith(Route::currentRouteName(), 'admin.user.') || Str::startsWith(Route::currentRouteName(), 'admin.vendor.') || Str::startsWith(Route::currentRouteName(), 'admin.admin.') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-user"></i>
                <div data-i18n="Users">Users</div>
            </a>
            <ul class="menu-sub">
                @if ($customerView)
                <li
                    class="menu-item {{ Str::startsWith(Route::currentRouteName(), 'admin.user.index') ? 'active' : '' }}">
                    <a href="{{ route('admin.user.index') }}" class="menu-link">
                        <div data-i18n="Customers">Customers</div>
                    </a>
                </li>
                @endif
                @if ($vendorView)
                <li class="menu-item {{ Str::startsWith(Route::currentRouteName(), 'admin.vendor.') ? 'active' : '' }}">
                    <a href="{{ route('admin.vendor.index') }}" class="menu-link">
                        <div data-i18n="Vendors">Vendors</div>
                    </a>
                </li>
                @endif
                @if ($superAdmin)
                <li class="menu-item {{ Str::startsWith(Route::currentRouteName(), 'admin.admin.') ? 'active' : '' }}">
                    <a href="{{ route('admin.admin.index') }}" class="menu-link">
                        <div data-i18n="Admins">Admins</div>
                    </a>
                </li>
                @endif
            </ul>
        </li>
        @endif

        @php
        $bannerView = auth('admin')->user()->roleRel?->permissions->contains('name', 'banner_view') || $superAdmin;
        @endphp
        @if ($bannerView)
        <li class="menu-item {{ Str::startsWith(Route::currentRouteName(), 'admin.banner.') ? 'active' : '' }}">
            <a href="{{ route('admin.banner.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-image"></i>
                <div data-i18n="Banners">Banners</div>
            </a>
        </li>
        @endif

        @php
        $categoryView =
        auth('admin')->user()->roleRel?->permissions->contains('name', 'category_view') || $superAdmin;
        $shopView = auth('admin')->user()->roleRel?->permissions->contains('name', 'shop_view') || $superAdmin;
        $colorView = auth('admin')->user()->roleRel?->permissions->contains('name', 'color_view') || $superAdmin;
        $heightView = auth('admin')->user()->roleRel?->permissions->contains('name', 'height_view') || $superAdmin;
        $productAttributeSetView = auth('admin')->user()->roleRel?->permissions->contains('name', 'attribute_view') ||
        $superAdmin;
        $calendarView =
        auth('admin')->user()->roleRel?->permissions->contains('name', 'calendar_view') || $superAdmin;
        @endphp
        @if ($shopView)
        <li
            class="menu-item {{ Str::startsWith(Route::currentRouteName(), 'admin.shop.') && !Str::startsWith(Route::currentRouteName(), 'admin.shop.category') ? 'active' : '' }}">
            <a href="{{ route('admin.shop.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-store"></i>
                <div data-i18n="Shops">Shops</div>
            </a>
        </li>
        @endif
        @if ($colorView)
        <li
            class="menu-item {{ Str::startsWith(Route::currentRouteName(), 'admin.color.') && !Str::startsWith(Route::currentRouteName(), 'admin.shop.category') ? 'active' : '' }}">
            <a href="{{ route('admin.color.index') }}" class="menu-link">
                <i class="menu-icon tf-icons fa fa-palette"></i>
                <div data-i18n="Colors">Colors</div>
            </a>
        </li>
        @endif
        @if ($heightView)
        <li
            class="menu-item {{ Str::startsWith(Route::currentRouteName(), 'admin.height.') && !Str::startsWith(Route::currentRouteName(), 'admin.shop.category') ? 'active' : '' }}">
            <a href="{{ route('admin.height.index') }}" class="menu-link">
                <i class="menu-icon tf-icons fa fa-balance-scale"></i>
                <div data-i18n="Height Units">Height Units</div>
            </a>
        </li>
        @endif
        @if ($productAttributeSetView)
        <li
            class="menu-item {{ Str::startsWith(Route::currentRouteName(), 'admin.productattributeset.') && !Str::startsWith(Route::currentRouteName(), 'admin.shop.category') ? 'active' : '' }}">
            <a href="{{ route('admin.productattributeset.index') }}" class="menu-link">
                <i class="menu-icon tf-icons fa fa-balance-scale"></i>
                <div data-i18n="Product Attribute Set">Product Attribute Set</div>
            </a>
        </li>
        @endif
        @if ($categoryView)
        <li class="menu-item {{ Str::contains(Route::currentRouteName(), 'admin.cms.') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-home"></i>
                <div data-i18n="CMS">CMS</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item" id="cms-about-us">
                    <a href="{{ route('admin.cms.get', 'about-us') }}" class="menu-link">
                        <div data-i18n="About Us">About Us</div>
                    </a>
                </li>
                <li class="menu-item" id="cms-term-n-condition">
                    <a href="{{ route('admin.cms.get', 'term-n-condition') }}" class="menu-link">
                        <div data-i18n="Terms & Condition">Terms & Condition</div>
                    </a>
                </li>
                <li class="menu-item" id="cms-faqs">
                    <a href="{{ route('admin.cms.get', 'faqs') }}" class="menu-link">
                        <div data-i18n="FAQS">FAQS</div>
                    </a>
                </li>
                <li class="menu-item" id="cms-privacy-policy">
                    <a href="{{ route('admin.cms.get', 'privacy-policy') }}" class="menu-link">
                        <div data-i18n="Privacy Policy">Privacy Policy</div>
                    </a>
                </li>
                <li class="menu-item" id="cms-how-it-works">
                    <a href="{{ route('admin.cms.get', 'how-it-works') }}" class="menu-link">
                        <div data-i18n="How It Works">How It Works</div>
                    </a>
                </li>
            </ul>
        </li>
        @endif
        @if ($categoryView)
        <li class="menu-item {{ Str::contains(Route::currentRouteName(), 'admin.category.') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons fa fa-cubes"></i>
                <div data-i18n="Categories">Categories</div>
            </a>
            <ul class="menu-sub">
                {{-- <li
                    class="menu-item {{ Str::startsWith(Route::currentRouteName(), 'admin.shop.category.') ? 'active' : '' }}">
                    <a href="{{ route('admin.shop.category.index') }}" class="menu-link">
                        <div data-i18n="Shop Category">Shop Category</div>
                    </a>
                </li> --}}
                <li
                    class="menu-item {{ Str::startsWith(Route::currentRouteName(), 'admin.category.') ? 'active' : '' }}">
                    <a href="{{ route('admin.category.index') }}" class="menu-link">
                        <div data-i18n="Product Category">Product Category</div>
                    </a>
                </li>
            </ul>
        </li>
        @endif
        @if ($calendarView)
        <li class="menu-item {{ Str::contains(Route::currentRouteName(), 'admin.calendar.') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons fa fa-calendar"></i>
                <div data-i18n="Delivery Calendar">Delivery Calendar</div>
            </a>
            <ul class="menu-sub">
                <li
                    class="menu-item {{ Str::startsWith(Route::currentRouteName(), 'admin.calendar.day') ? 'active' : '' }}">
                    <a href="{{ route('admin.calendar.day') }}" class="menu-link">
                        <div data-i18n="Days">Days</div>
                    </a>
                </li>
                <li
                    class="menu-item {{ Str::startsWith(Route::currentRouteName(), 'admin.calendar.time') ? 'active' : '' }}">
                    <a href="{{ route('admin.calendar.time') }}" class="menu-link">
                        <div data-i18n="Times">Times</div>
                    </a>
                </li>
            </ul>
        </li>
        @endif

        @php
        $couponView = auth('admin')->user()->roleRel?->permissions->contains('name', 'coupon_view') || $superAdmin;
        @endphp

        @if ($couponView)
        <li class="menu-item {{ Str::startsWith(Route::currentRouteName(), 'admin.coupon.') ? 'active' : '' }}">
            <a href="{{ route('admin.coupon.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-gift"></i>
                <div data-i18n="Coupons">Coupons</div>
            </a>
        </li>
        @endif

        @php
        $orderView = auth('admin')->user()->roleRel?->permissions->contains('name', 'order_view') ||
        $superAdmin;
        @endphp

        @if ($orderView)
        <li class="menu-item {{ Str::startsWith(Route::currentRouteName(), 'admin.order.') ? 'active' : '' }}">
            <a href="{{ route('admin.order.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-shopping-bag"></i>
                <div data-i18n="Orders">Orders</div>
            </a>
        </li>
        @endif

        @php
        $subscriptionView = auth('admin')->user()->roleRel?->permissions->contains('name', 'subscription_view') ||
        $superAdmin;
        @endphp


        @if ($subscriptionView)
        <li class="menu-item {{ Str::contains(Route::currentRouteName(), 'admin.plan.') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons fa fa-calendar"></i>
                <div data-i18n="Subscriptions">Subscriptions</div>
            </a>
            <ul class="menu-sub">
                <li
                    class="menu-item {{ Str::startsWith(Route::currentRouteName(), 'admin.plan.subscription.') ? 'active' : '' }}">
                    <a href="{{ route('admin.plan.subscription.index') }}" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-shopping-bag"></i>
                        <div data-i18n="Subscriptions">Subscriptions</div>
                    </a>
                </li>
                <li
                    class="menu-item {{ Str::startsWith(Route::currentRouteName(), 'admin.plan.subscriber.') ? 'active' : '' }}">
                    <a href="{{ route('admin.plan.subscriber.view') }}" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-shopping-bag"></i>
                        <div data-i18n="Subscribers">Subscribers</div>
                    </a>
                </li>
            </ul>
        </li>

        @endif

        @if ($superAdmin)
        <li class="menu-item {{ Str::startsWith(Route::currentRouteName(), 'admin.role.') ? 'active' : '' }}">
            <a href="{{ route('admin.role.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-check-shield"></i>
                <div data-i18n="Roles & Permissions">Roles & Permissions</div>
            </a>
        </li>
        @endif
        @if ($superAdmin || auth('admin')->user()->roleRel?->permissions->contains('name', 'report_shop'))
        <li class="menu-item {{ Str::startsWith(Route::currentRouteName(), 'report.') ? 'active' : '' }}">
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