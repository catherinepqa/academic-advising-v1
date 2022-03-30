<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Reset Password</title>
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
                                                <!--<img src="../assets/images/logo-light.png" alt="" height="22">-->
                                            </span>
                                        </a>
                                    </div>
                                    <p class="text-muted mb-4 mt-3">Enter your new password.</p>
                                </div>

                                <form id="form">

                                    <div class="form-group mb-3">
                                        <label for="emailaddress">New Password</label>
                                        <input class="form-control" type="password" name="new_password" id="emailaddress" required="" placeholder="Enter your new password">
                                        <input type="hidden" name="user_id" value="{{ $user_id }}">
                                    </div>

                                    <div class="btn btn-block hidden spinner_div">
                                        <div class="spinner-border" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                    </div>
                                    <div class="form-group mb-0 text-center res_div">
                                        <button id="resetBtn" class="btn btn-primary btn-block" type="button"> Reset Password </button>
                                    </div>

                                </form>

                            </div> <!-- end card-body -->
                        </div>
                        <!-- end card -->

                        <div class="row mt-3">
                            <div class="col-12 text-center">
                                {{-- <p class="text-white-50">Back to <a href="auth-login.html" class="text-white ml-1"><b>Log in</b></a></p> --}}
                            </div> <!-- end col -->
                        </div>
                        <!-- end row -->

                    </div> <!-- end col -->
                </div>
                <!-- end row -->
            </div>
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
           $('#resetBtn').click(function(){
                var add_url = "{{ route('changePassword') }}";
                var index_url = "{{ route('adminLogIn') }}";
                data = $("#form").serialize();

                if ($('#form').parsley().validate()) {
                    $('.res_div').addClass('hidden');
                    $('.spinner_div').removeClass('hidden');
                    $.ajax({
                        url: add_url,
                        type: 'POST',
                        data: data,
                        dataType: 'json',
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        success: function(data) {
                            $('.res_div').removeClass('hidden');
                            $('.spinner_div').addClass('hidden');
                            if (data.message == 'success') {
                                swal({
                                    title: "Success!",
                                    text: "You successfully changed your password",
                                    type: "success",
                                    confirmButtonColor:"#f2993e",
                                }).then(function() {
                                    window.location.href = index_url;
                                });
                            } else {
                                swal({
                                    title: "Error!",
                                    text: data.message['message'],
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