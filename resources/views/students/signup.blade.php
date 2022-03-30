<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Student | Signup</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
        <meta content="Coderthemes" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />

        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ URL::asset('assets/images/logo.png') }}">
        <!-- App css -->
        <link href="{{ URL::asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" id="bs-default-stylesheet" />
        <link href="{{ URL::asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" id="app-default-stylesheet" />

        <link href="{{ URL::asset('assets/css/bootstrap-dark.min.css') }}" rel="stylesheet" type="text/css" id="bs-dark-stylesheet" disabled />
        <link href="{{ URL::asset('assets/css/app-dark.min.css') }}" rel="stylesheet" type="text/css" id="app-dark-stylesheet"  disabled />

        <!-- icons -->
        <link href="{{ URL::asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />

        <!-- Plugin CSS -->
        <link href="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="{{ URL::asset('assets/libs/parsleyjs/css/parsley.css') }}">

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
                                                <img src="../assets/images/logo-light.png" alt="" height="22">
                                            </span>
                                        </a>
                                    </div>
                                    <p class="text-muted mb-4 mt-3">Don't have an account? Create your account, it takes less than a minute</p>
                                </div>

                                <form id="sign-up-form" action="#">

                                   <div class="row">
                                        <div class="form-group col-lg-6">
                                            <label for="fullname">First Name</label>
                                            <input class="form-control" type="text" name="first_name" id="fullname" placeholder="Enter your first name" required>
                                        </div>
                                        <div class="form-group col-lg-6">
                                            <label for="fullname">Last Name</label>
                                            <input class="form-control" type="text" name="last_name" id="" placeholder="Enter your last name" required>
                                        </div>
                                        <div class="form-group col-lg-12">
                                            <label for="emailaddress">Email address</label>
                                            <input class="form-control" type="email" name="email" id="emailaddress" required placeholder="Enter your email">
                                            <input type="hidden" name="role" value="student">
                                        </div>
                                        <div class="form-group col-lg-12">
                                            <label for="password">Password</label>
                                            <div class="input-group input-group-merge">
                                                <input type="password" id="password" name="password" class="form-control" required placeholder="Enter your password" data-parsley-errors-container="#details-errors">
                                                <div class="input-group-append" data-password="false">
                                                    <div class="input-group-text">
                                                        <span class="password-eye"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="details-errors"></div>
                                        </div>
                                   </div>
                                    <div class="btn btn-block hidden spinner_div">
                                        <div class="spinner-border" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                    </div>
                                    <div class="form-group mb-0 text-center signup_btn_div">
                                        <button id="signupBtn" class="btn btn-primary btn-block" type="button"> Sign Up </button>
                                    </div>

                                </form>

                            </div> <!-- end card-body -->
                        </div>
                        <!-- end card -->

                        <div class="row mt-3">
                            <div class="col-12 text-center">
                                <p class="text-white-50">Already have account?  <a href="{{ route('studentLogIn') }}" class="text-white ml-1"><b>Sign In</b></a></p>
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
        <script src="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
        <script src="{{ URL::asset('assets/libs/parsleyjs/js/parsley.min.js') }}"></script>

        <script>
            $('#signupBtn').click(function(){
                var add_url = "{{ route('signUpProcess') }}";
                var index_url = "{{ route('studentLogIn') }}";
                data = $("#sign-up-form").serialize();

                if ($('#sign-up-form').parsley().validate()) {
                    $('.signup_btn_div').addClass('hidden');
                    $('.spinner_div').removeClass('hidden');
                    $.ajax({
                        url: add_url,
                        type: 'POST',
                        data: data,
                        dataType: 'json',
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        success: function(data) {
                            $('.signup_btn_div').removeClass('hidden');
                            $('.spinner_div').addClass('hidden');
                            if (data.msg == 'success') {
                                swal({
                                    title: "Success!",
                                    text: "You successfully registered. Please verify your email address",
                                    type: "success",
                                    confirmButtonColor:"#f2993e",
                                }).then(function() {
                                    window.location.href = index_url;
                                });
                            } else {
                                swal({
                                    title: "Error!",
                                    text: data.msg['message'],
                                    type: "error"
                                });
                            }
                        },
                        error : function(request, status, error) {
                            swal("Oops!", "Seems like there is an error. Please try again", "error");
                        }
                    });
                }
            });
        </script>
        
    </body>
</html>