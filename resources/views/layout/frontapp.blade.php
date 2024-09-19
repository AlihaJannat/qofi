<!DOCTYPE html>
<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed" dir="ltr" data-theme="theme-default"
    data-assets-path="{{ asset('admindic') }}/" data-template="vertical-menu-template">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ app_setting('site_name') }} @yield('title')</title>

    <meta name="description" content="Start your development with a Dashboard for Bootstrap 5" />
    <meta name="keywords"
        content="dashboard, bootstrap 5 dashboard, bootstrap 5 admin, bootstrap 5 design, bootstrap 5">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon"
        href="{{ asset(app_setting('site_favicon', 'assets/images/logo-png/favicon.ico')) }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('admindic/vendor/fonts/boxicons.css') }}" />
    <link rel="stylesheet" href="{{ asset('admindic/vendor/fonts/fontawesome.css') }}" />
    <link rel="stylesheet" href="{{ asset('admindic/vendor/fonts/flag-icons.css') }}" />
    <link rel="stylesheet" href="{{ asset('frontend/css/navbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('frontend/css/main.css') }}" />

    <link rel="stylesheet"
        href="{{ asset('admindic/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('admindic/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('admindic/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('admindic/vendor/libs/bootstrap-select/bootstrap-select.css') }}" />
    <link rel="stylesheet" href="{{ asset('admindic/vendor/libs/sweetalert2/sweetalert2.css') }}" />
    <!-- Owl Carousel CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">

    @vite('resources/css/app.css')

    <style>
        .show-submenu {
            display: block !important;
        }
    </style>

    @yield('style')
</head>

<body class="bg-site-bg font-roboto">
    @include('include.frontnav')
    <!-- / Layout wrapper -->
    <div class="content min-h-[80vh]">
        @yield('content')
    </div>
    @include('include.frontfooter')

    <!-- Core JS -->
    <script src="{{ asset('admindic/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('admindic/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('admindic/vendor/js/bootstrap.js') }}"></script>

    <!-- Page JS -->
    <script src="{{ asset('admindic/js/forms-selects.js') }}"></script>
    <script src="{{ asset('admindic/vendor/libs/moment/moment.js') }}"></script>
    <script src="{{ asset('admindic/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="{{ asset('admindic/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('admindic/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
    <script src="{{ asset('frontend/js/navbar.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

    <script>
        function validateFile(image, sizeInMb, imageCheck, isSvg = false) {
            if (imageCheck) {
                if (!image.type.startsWith('image/')) {
                    Swal.fire({
                        icon: 'error',
                        title: '',
                        text: "Please select only image files.",
                        confirmButtonColor: '#dc3545',
                    })
                    return false;
                }
                if (isSvg) {
                    if (!image.type.startsWith('image/svg+xml')) {
                        Swal.fire({
                            icon: 'error',
                            title: '',
                            text: "Please select only SVG files.",
                            confirmButtonColor: '#dc3545',
                        })
                        return false;
                    }
                }
            }
            if (image.size > sizeInMb * 1024 * 1024) {
                Swal.fire({
                    icon: 'error',
                    title: 'File size too large',
                    text: 'Please select an image file smaller than 2MB.',
                    confirmButtonColor: '#dc3545',
                });
                return false;
            }
            return true;
        }

        var addToWishlist;
        $(document).ready(function() {
            addToWishlist = function(id, btn) {
                addUrl = "{{ route('wishlist.add') }}";
                removeUrl = "{{ route('wishlist.remove') }}";
                const callUrl = $(btn).hasClass('far') ? addUrl : removeUrl;
                $.ajax({
                    accept: 'application/json',
                    url: callUrl,
                    method: 'POST',
                    data: {
                        product_id: id,
                        _token: "{{ csrf_token() }}",
                    },
                    success: function(data) {
                        if ($(btn).hasClass('far')) {
                            $(btn).removeClass('far')
                            $(btn).removeClass('text-black-2')
                            $(btn).addClass('fa')
                            $(btn).addClass('text-btn-pink')
                        } else {
                            $(btn).addClass('far')
                            $(btn).addClass('text-black-2')
                            $(btn).removeClass('fa')
                            $(btn).removeClass('text-btn-pink')
                        }
                    },
                    error: function(data) {
                        if (data.status == 401) {
                            Swal.fire({
                                title: "Unable to Add",
                                text: "Please login to add items in your wishlist",
                                icon: "error",
                            })

                            return false;
                        }
                        const message = data.responseJSON?.message
                        Swal.fire({
                            title: "Unable to Process",
                            text: message ? message : "Something went wrong",
                            icon: "error",
                        })
                    }
                })
            }
        });
    </script>

    @yield('script')
</body>

</html>
