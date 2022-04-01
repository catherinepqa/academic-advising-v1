<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Teacher | Log In</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
        <meta content="Coderthemes" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ URL::asset('assets/images/logo.png') }}">
		<!-- App css -->
		<link href="{{ URL::asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" id="bs-default-stylesheet" />
		<link href="{{ URL::asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" id="app-default-stylesheet" />

		<link href="{{ URL::asset('assets/css/bootstrap-dark.min.css') }}" rel="stylesheet" type="text/css" id="bs-dark-stylesheet" disabled />
		<link href="{{ URL::asset('assets/css/app-dark.min.css') }}" rel="stylesheet" type="text/css" id="app-dark-stylesheet"  disabled />

		<!-- icons -->
		<link href="{{ URL::asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
        
         <!-- Custom CSS -->
         <link href="{{ URL::asset('assets/css/custom.css') }}" rel="stylesheet" type="text/css" />

         <style>
            .logo-lg img {
                margin-left: -5%;
            }
        </style>

    </head>

    <body class="authentication-bg authentication-bg-pattern">

        <div class="account-pages mt-5 mb-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-5">
                        <div class="card bg-pattern">

                            <div class="card-body p-4">
                                
                                <div class="text-center w-75 m-auto">
                                    <div class="auth-logo">
                                        <a href="index.html" class="logo logo-dark text-center">
                                            <span class="logo-lg">
                                                <img src="../assets/images/logo-1.png" alt="" height="100">
                                            </span>
                                        </a>
                    
                                        <a href="index.html" class="logo logo-light text-center">
                                            <span class="logo-lg">
                                                <!-- <img src="{{ URL::asset('assets/images/logo-light.png') }}" alt="" height="22"> -->
                                            </span>
                                        </a>
                                    </div>
                                    <p class="text-muted mb-4 mt-3">Enter your email address and password to your teacher account.</p>
                                </div>

                                <form method="post" action="{{ route('teacherLoginProcess') }}">
                                    @csrf
                                    @if(\Illuminate\Support\Facades\Session::has('message'))
                                        <p class="alert {{ \Illuminate\Support\Facades\Session::get('alert-class', 'alert-info') }}">{{ \Illuminate\Support\Facades\Session::get('message') }}</p>
                                    @endif
                                    <div class="form-group mb-3">
                                        <label for="emailaddress">Email address</label>
                                        <input class="form-control" type="email" id="emailaddress" name="email" required="" placeholder="Enter your email">
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="password">Password</label>
                                        <div class="input-group input-group-merge">
                                            <input type="password" id="password" class="form-control" name="password" placeholder="Enter your password">
                                            <div class="input-group-append" data-password="false">
                                                <div class="input-group-text">
                                                    <span class="password-eye"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- <div class="form-group mb-3">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="checkbox-signin" checked>
                                            <label class="custom-control-label" for="checkbox-signin">Remember me</label>
                                        </div>
                                    </div> -->

                                    <div class="form-group mb-0 text-center">
                                        <button class="btn btn-primary btn-block" type="submit"> Log In </button>
                                    </div>

                                </form>
                            </div> <!-- end card-body -->
                        </div>
                        <!-- end card -->

                        <div class="row mt-3">
                            <div class="col-12 text-center">
                                <p> <a href="{{ route('forgotPass') }}" class="text-white-50 ml-1">Forgot your password?</a></p>
                            </div> <!-- end col -->
                        </div>
                        <!-- end row -->

                    </div> <!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>
        <!-- end page -->

        
        <footer class="footer footer-alt">
            <script>document.write(new Date().getFullYear())</script> &copy; A web-application on student's academic advising
        </footer>

        <!-- Vendor js -->
        <script src="{{ URL::asset('assets/js/vendor.min.js') }}"></script>

        <!-- App js -->
        <script src="{{ URL::asset('assets/js/app.min.js') }}"></script>
        
    </body>
</html>