<!DOCTYPE html>

<html lang="en" class="light-style  customizer-hide" dir="ltr" data-theme="theme-default" data-assets-path="{{asset('admindic')}}/" data-template="vertical-menu-template">
<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>{{ app_setting('site_name') }} | Admin Login</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon"
        href="{{asset(app_setting('site_favicon', 'assets/images/logo-png/favicon.ico'))}}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&amp;family=Rubik:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&amp;display=swap"
        rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="{{asset('admindic/vendor/fonts/boxicons.css')}}" />
    <link rel="stylesheet" href="{{asset('admindic/vendor/fonts/fontawesome.css')}}" />
    <link rel="stylesheet" href="{{asset('admindic/vendor/fonts/flag-icons.css')}}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{asset('admindic/vendor/css/rtl/core.css')}}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{asset('admindic/vendor/css/rtl/theme-default.css')}}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{asset('admindic/css/demo.css')}}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{asset('admindic/vendor/libs/perfect-scrollbar/perfect-scrollbar.css')}}" />
    <link rel="stylesheet" href="{{asset('admindic/vendor/libs/typeahead-js/typeahead.css')}}" />
    <!-- Vendor -->
    <link rel="stylesheet" href="{{asset('admindic/vendor/libs/formvalidation/dist/css/formValidation.min.css')}}" />

    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="{{asset('admindic/vendor/css/pages/page-auth.css')}}">
    <!-- Helpers -->
    <script src="{{asset('admindic/vendor/js/helpers.js')}}"></script>

    <script src="{{asset('admindic/vendor/js/template-customizer.js')}}"></script>

    <script src="{{asset('admindic/js/config.js')}}"></script>
    <style>
        .layout-menu-toggle .menu-toggle-icon::before {
            content: "" !important;
        }
    </style>
</head>

<body>
    <!-- Content -->

    <div class="authentication-wrapper authentication-cover">
        <div class="authentication-inner row d-flex align-items-center justify-content-center">
            <!-- Login -->
            <div class="d-flex col-12 col-lg-5 col-xl-4 align-items-center authentication-bg p-sm-5 p-4">
                <div class="w-px-400 mx-auto">
                    <!-- Logo -->
                    <div class="app-brand mb-4">
                        <a href="#" class="app-brand-link gap-2 mb-2">
                            <span class="app-brand-logo demo">
                                <img src="{{ app_setting('site_logo') }}" alt="logo" height="26px" width="26px" class="logo">

                            </span>
                            <span class="app-brand-text demo h3 mb-0 fw-bold">{{ app_setting('site_name') }}</span>
                        </a>
                    </div>
                    <!-- /Logo -->
                    <h4 class="mb-2">Welcome to {{ app_setting('site_name') }}! 👋</h4>
                    <p class="mb-4">Please sign-in to your account as Admin</p>

                    <form id="login-form" class="mb-3" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="text" class="form-control" id="email" name="email"
                                placeholder="Enter your email" autofocus required>
                        </div>
                        <div class="mb-3 form-password-toggle">
                            <div class="d-flex justify-content-between">
                                <label class="form-label" for="password">Password</label>
                            </div>
                            <div class="input-group input-group-merge">
                                <input type="password" id="password" class="form-control" name="password"
                                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                    aria-describedby="password" required/>
                            </div>
                        </div>
                        <span id="login-response"></span>
                        <button class="btn btn-primary d-grid w-100" id="login-button">
                            Login
                        </button>
                    </form>
                </div>
            </div>
            <!-- /Login -->
        </div>
    </div>

    <!-- / Content -->







    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="{{asset('admindic/vendor/libs/jquery/jquery.js')}}"></script>
    <script src="{{asset('admindic/vendor/libs/popper/popper.js')}}"></script>
    <script src="{{asset('admindic/vendor/js/bootstrap.js')}}"></script>
    <script src="{{asset('admindic/vendor/libs/perfect-scrollbar/perfect-scrollbar.js')}}"></script>

    <script src="{{asset('admindic/vendor/libs/hammer/hammer.js')}}"></script>


    <script src="{{asset('admindic/vendor/libs/i18n/i18n.js')}}"></script>
    <script src="{{asset('admindic/vendor/libs/typeahead-js/typeahead.js')}}"></script>

    <script src="{{asset('admindic/vendor/js/menu.js')}}"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="{{asset('admindic/vendor/libs/formvalidation/dist/js/FormValidation.min.js')}}"></script>
    <script src="{{asset('admindic/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js')}}"></script>
    <script src="{{asset('admindic/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js')}}"></script>

    <!-- Main JS -->
    <script src="{{asset('admindic/js/main.js')}}"></script>

    <script>
        $(document).ready(function() {
            var loginForm = $('#login-form');
            loginForm.submit(function(e) {
                e.preventDefault();
                $('#login-button').attr("disabled", true);
                $('#login-button').html("Please Wait...");
                var formData = loginForm.serialize();
                // console.log(formData);
                $.ajax({
                    url: "{{ route('admin.login') }}",
                    method: 'POST',
                    data: formData,
                    success: function(data) {
                        $('#login-button').attr("disabled", true);
                        $('#login-button').html("Loging in...");
                        $('#login-response').html("Login Success")
                        $('#login-response').css({
                            'color': 'green'
                        })
                        setTimeout(
                            function() {
                                $(location).attr('href', '{{ route('admin.dash') }}');
                            }, 1000);
                    },
                    error: function(data) {
                        $('#login-button').attr("disabled", false);
                        $('#login-button').html("Login");
                        $('#login-response').html("Login Failed: Email or Password Incorrect")
                        $('#login-response').css({
                            'color': 'red'
                        })
                    }
                })
            });
        })
    </script>
</body>

</html>
