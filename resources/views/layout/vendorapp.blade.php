<!DOCTYPE html>

<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed " dir="ltr" data-theme="theme-default"
    data-assets-path="{{ asset('admindic') }}/" data-template="vertical-menu-template">



<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Dashboard - Vendor | {{ app_setting('site_name') }}</title>


    <meta name="description" content="Start your development with a Dashboard for Bootstrap 5" />
    <meta name="keywords"
        content="dashboard, bootstrap 5 dashboard, bootstrap 5 admin, bootstrap 5 design, bootstrap 5">
    <!-- Canonical SEO -->
    <link rel="canonical" href="https://1.envato.market/frest_admin">




    <!-- Favicon -->
    <link rel="icon" type="image/x-icon"
        href="{{ asset(app_setting('site_favicon', 'assets/images/logo-png/favicon.ico')) }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&amp;family=Rubik:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&amp;display=swap"
        rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('admindic/vendor/fonts/boxicons.css') }}" />
    <link rel="stylesheet" href="{{ asset('admindic/vendor/fonts/fontawesome.css') }}" />
    <link rel="stylesheet" href="{{ asset('admindic/vendor/fonts/flag-icons.css') }}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('admindic/vendor/css/rtl/core.css') }}"
        class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('admindic/vendor/css/rtl/theme-default.css') }}"
        class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('admindic/css/demo.css') }}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('admindic/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('admindic/vendor/libs/typeahead-js/typeahead.css') }}" />
    <link rel="stylesheet" href="{{ asset('admindic/vendor/libs/apex-charts/apex-charts.css') }}" />

    <link rel="stylesheet" href="{{ asset('admindic/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}">
    <link rel="stylesheet"
        href="{{ asset('admindic/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('admindic/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}"> 
    <link rel="stylesheet" href="{{ asset('admindic/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('admindic/vendor/libs/bootstrap-select/bootstrap-select.css') }}" />
    <link rel="stylesheet" href="{{ asset('admindic/vendor/libs/sweetalert2/sweetalert2.css') }}" />

    <link rel="stylesheet" href="{{ asset('admindic/vendor/libs/leaflet/leaflet.css') }}" />

    <!-- Helpers -->
    <script src="{{ asset('admindic/vendor/js/helpers.js') }}"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
    <script src="{{ asset('admindic/vendor/js/template-customizer.js') }}"></script>
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ asset('admindic/js/config.js') }}"></script>
    <style>
        .layout-menu-toggle .menu-toggle-icon::before {
            content: "" !important;
        }

        #template-customizer {
            display: none !important;
        }
    </style>
</head>

<body>



    <div class="layout-wrapper layout-content-navbar  ">
        <div class="layout-container">
            @include('include.vendorside')
        </div>

        <div class="layout-page">
            @include('include.vendornav')
            <div class="content-wrapper">
                @yield('content')

                <!-- Footer -->
                @include('include.adminfooter')
                <!-- / Footer -->

                <div class="content-backdrop fade"></div>
            </div>
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>


        <!-- Drag Target Area To SlideIn Menu On Small Screens -->
        <div class="drag-target"></div>

    </div>
    <!-- / Layout wrapper -->


    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="{{ asset('admindic/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('admindic/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('admindic/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('admindic/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>

    <script src="{{ asset('admindic/vendor/libs/hammer/hammer.js') }}"></script>


    <script src="{{ asset('admindic/vendor/libs/i18n/i18n.js') }}"></script>
    <script src="{{ asset('admindic/vendor/libs/typeahead-js/typeahead.js') }}"></script>

    <script src="{{ asset('admindic/vendor/js/menu.js') }}"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="{{ asset('admindic/vendor/libs/apex-charts/apexcharts.js') }}"></script>

    <!-- Main JS -->
    <script src="{{ asset('admindic/js/main.js') }}"></script>

    <!-- Page JS -->
    <script src="{{ asset('admindic/js/dashboards-analytics.js') }}"></script>
    <script src="{{ asset('admindic/js/forms-selects.js') }}"></script>
    <script src="{{ asset('admindic/vendor/libs/moment/moment.js') }}"></script>
    <script src="{{ asset('admindic/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="{{ asset('admindic/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('admindic/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>

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
        var shopDiscountChangeSubmit
        $(document).ready(function() {
            shopDiscountChangeSubmit = function(e, form) {
                e.preventDefault();
                $.ajax({
                    dataType: 'json',
                    contentType: false,
                    cache: false,
                    processData: false,
                    url: $(form).attr('action'),
                    method: 'POST',
                    data: new FormData(form),
                    success: function(data) {
                        // table.draw(false)
                        $('.btn-reset').click()
                        Swal.fire({
                            title: `Your Request has been Submitted`,
                            icon: "info",
                            confirmButtonText: 'OK',
                        })
                    },
                    error: function(data) {
                        if (data.responseJSON?.message) {
                            Swal.fire({
                                title: `Oops!`,
                                text: data.responseJSON.message,
                                icon: "warning",
                            })
                            return false;
                        }
                        Swal.fire({
                            title: `Something went wrong!`,
                            icon: "warning",
                        })
                    }
                })
            }
        })
    </script>
    @yield('script')
</body>


</html>
