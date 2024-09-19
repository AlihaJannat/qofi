<nav class="layout-navbar navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar">
    <div class="container-fluid">
        <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0   d-xl-none ">
            <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                <i class="bx bx-menu bx-sm"></i>
            </a>
        </div>


        <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
            <ul class="navbar-nav flex-row align-items-center ms-auto">

                @php
                    $currentVendor = auth('vendor')->user();
                    // $myShops = $currentVendor->getMyShops;
                @endphp
                {{-- @if (count($myShops))
                    <li class="nav-item navbar-dropdown dropdown-user dropdown">
                        <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);"
                            data-bs-toggle="dropdown">
                            Selected Shop: {{ $myShops->where('id', $currentVendor->sw_shop_id)->first()?->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            @foreach ($myShops as $shop)
                                <li>
                                    <a class="dropdown-item" href="{{ route('vendor.change.shop', $shop->id) }}">
                                        <b>{{ $shop->name }}</b>
                                    </a>
                                </li>
                                <li>
                                    <div class="dropdown-divider"></div>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @endif --}}

                <!-- Style Switcher -->
                <li class="nav-item me-2 me-xl-0">
                    <a class="nav-link style-switcher-toggle hide-arrow" href="javascript:void(0);">
                        <i class='bx bx-sm'></i>
                    </a>
                </li>
                <!--/ Style Switcher -->
                <!-- User -->
                <li class="nav-item navbar-dropdown dropdown-user dropdown">
                    <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                        <div class="avatar avatar-online">
                            <img src="{{ asset('images' . $currentVendor->image) }}"
                                onerror="this.onerror=null; this.src='{{ asset('images/user/default.jpg') }}';"
                                alt="profile" class="rounded-circle">
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="#">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="avatar avatar-online">
                                            <img src="{{ asset('images' . $currentVendor->image) }}"
                                                onerror="this.onerror=null; this.src='{{ asset('images/user/default.jpg') }}';"
                                                alt class="rounded-circle">
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <span
                                            class="fw-semibold d-block lh-1 text-capitalize">{{ $currentVendor->name }}</span>
                                        <small class="text-capitalize"><b>Role:</b>
                                            {{ $currentVendor->roleRel?->name }}</small>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <div class="dropdown-divider"></div>
                        </li>
                        @php
                            $shop = $currentVendor->shop;
                        @endphp
                        <li>
                            <a class="dropdown-item" href="#">
                                <b>Shop:</b> {{ $shop?->name }}
                            </a>
                        </li>
                        <li>
                            <div class="dropdown-divider"></div>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('vendor.logout') }}">
                                <i class="bx bx-power-off me-2"></i>
                                <span class="align-middle">Log Out</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <!--/ User -->
            </ul>
        </div>
    </div>
</nav>
