<!DOCTYPE html>

<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed " dir="ltr" data-theme="theme-default"
    data-assets-path="{{ asset('admindic') }}/" data-template="vertical-menu-template">



<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Dashboard - Admin | {{ app_setting('site_name') }}</title>


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

        .chatbot-container {
            position: fixed;
            bottom: 60px;
            right: 10px;
            z-index: 1000;
            display: none;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .chatbot-container iframe {
            border: none;
            width: 350px;
            height: 430px;
            border-radius: 10px;
        }

        .toggle-button {
            position: fixed;
            bottom: 10px;
            right: 10px;
            z-index: 1001;
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>

</head>

<body>



    <div class="layout-wrapper layout-content-navbar  ">
        <div class="layout-container">
            @include('include.adminside')
        </div>

        <div class="layout-page">
            @include('include.adminnav')
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
        {{-- <div class="drag-target"></div>
        <div class="toggle-button" onclick="toggleChatbot()">
            <i class="fas fa-comments"></i>
        </div>
        <div class="chatbot-container" id="chatbot">
            <iframe allow="microphone;"
                src="https://console.dialogflow.com/api-client/demo/embedded/731a3f67-f483-4ddf-85e7-b6fc1229ac39"></iframe>
        </div> --}}

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
    <script src="{{ asset('admindic/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>
    <script src="{{ asset('admindic/vendor/libs/flatpickr/flatpickr.js') }}"></script>

    <script>
        function toggleChatbot() {
            var chatbot = document.getElementById('chatbot');
            if (chatbot.style.display === 'none' || chatbot.style.display === '') {
                chatbot.style.display = 'block';
            } else {
                chatbot.style.display = 'none';
            }
        }
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
    </script>
    @yield('script')

</body>


</html>