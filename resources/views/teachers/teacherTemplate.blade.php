<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>@yield('title')</title>
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
    <link href="{{ URL::asset('assets/css/custom.css') }}" rel="stylesheet" type="text/css" />

    <style>
        .content {
            margin-top: -4%
        }
        .logo-lg img {
            margin-top: -3%;
            margin-left: -5%;
        }
    </style>
    @yield('css')
</head>

<body data-layout-mode="horizontal" data-layout='{"mode": "light", "width": "fluid", "menuPosition": "fixed", "topbar": {"color": "dark"}, "showRightSidebarOnPageLoad": true}'>

<!-- Page Loader -->
<div id="preloader" class="hidden">
    <div id="status" class="hidden">
        <div class="spinner">Loading...</div>
    </div>
</div>

<!-- Begin page -->
<div id="wrapper">

    <!-- Topbar Start -->
    <div class="navbar-custom">
        <div class="container-fluid">
            <ul class="list-unstyled topnav-menu float-right mb-0">
                <li class="dropdown notification-list topbar-dropdown">
                    <a class="nav-link dropdown-toggle waves-effect waves-light" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                        <i data-feather="message-square"></i>
                        <p class="msg-count"></p>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-lg">

                        <!-- item-->
                        <div class="dropdown-item noti-title">
                            <h5 class="m-0">
                                <span class="float-right">
                                    <a href="" class="text-dark">
                                        <small>Clear All</small>
                                    </a>
                                </span>Messages
                            </h5>
                        </div>

                        <div class="msg"></div>

                        <!-- All-->
                        <a href="javascript:void(0);" class="dropdown-item text-center text-primary notify-item notify-all">
                            View all
                            <i class="fe-arrow-right"></i>
                        </a>

                    </div>
                </li>

                <li class="dropdown notification-list topbar-dropdown">
                    <a class="nav-link dropdown-toggle waves-effect waves-light" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                        <i data-feather="bell"></i>
                        <p class="notif-count"></p>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-lg">

                        <!-- item-->
                        <div class="dropdown-item noti-title">
                            <h5 class="m-0">
                                <span class="float-right">
                                    <a href="javascript:void(0);" onclick="clearAll()" class="text-dark">
                                        <small>Clear All</small>
                                    </a>
                                </span>Notifications
                            </h5>
                        </div>

                        <div class="noti"></div>

                        <!-- All-->
                        <a href="{{ route('teacherNotifications') }}" class="dropdown-item text-center text-primary notify-item notify-all">
                            View all
                            <i class="fe-arrow-right"></i>
                        </a>

                    </div>
                </li>

                <li class="dropdown notification-list topbar-dropdown">
                    <a class="nav-link dropdown-toggle nav-user mr-0 waves-effect waves-light" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                        @if (!empty(session('avatar')))
                            <img src="{{ URL::asset('assets/images/user_avatars/'.session('profile_id').'/'.session('avatar').'') }}" alt="user-image" class="rounded-circle">
                        
                        @else    
                            <img src="../assets/images/user.png" alt="user-image" class="rounded-circle">
                        @endif 
                        <span class="pro-user-name ml-1">
                            {{ session('first_name') }} <i class="mdi mdi-chevron-down"></i> 
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                        <!-- item-->
                        <div class="dropdown-header noti-title">
                            <h6 class="text-overflow m-0">Welcome !</h6>
                        </div>

                        <!-- item-->
                        <a href="{{ route('teacherProfile') }}" class="dropdown-item notify-item
                        {{ (request()->is('teacher/profile')) ? 'active' : '' }}">
                            <i class="fe-user"></i>
                            <span>My Account</span>
                        </a>

                        <a href="{{ route('teacherSchedule') }}" class="dropdown-item notify-item
                        {{ (request()->is('teacher/schedule')) ? 'active' : '' }}">
                            <i class="fe-calendar"></i>
                            <span>My Schedule</span>
                        </a>

                        <!--<a href="{{ route('teacherAppointment') }}" class="dropdown-item notify-item
                        {{ (request()->is('teacher/appointment')) ? 'active' : '' }}">
                            <i class="fe-clipboard"></i>
                            <span>Appointment List</span>
                        </a>-->

                        <div class="dropdown-divider"></div>

                        <!-- item-->
                        <a href="javascript:void(0);" class="dropdown-item logout notify-item">
                            <i class="fe-log-out"></i>
                            <span>Logout</span>
                        </a>

                    </div>
                </li>

            </ul>

            <!-- LOGO -->
            <div class="logo-box">
                <a href="#" class="logo logo-dark text-center">
                    <span class="logo-sm">
                        <img src="{{ URL::asset('assets/images/logo.png') }}" alt="" height="22">
                        <!-- <span class="logo-lg-text-light">UBold</span> -->
                    </span>
                    <span class="logo-lg">
                        <img src="{{ URL::asset('assets/images/logo-2.png') }}" alt="" height="20">
                        <!-- <span class="logo-lg-text-light">U</span> -->
                    </span>
                </a>

                <a href="{{ route('teacherDashboard') }}" class="logo logo-light text-center">
                    <span class="logo-sm">
                        <img src="{{ URL::asset('assets/images/logo.png') }}" alt="" height="60">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ URL::asset('assets/images/logo-2.png') }}" alt="" height="85">
                    </span>
                </a>
            </div>

            <ul class="list-unstyled topnav-menu topnav-menu-left m-0">
                <li>
                    <button class="button-menu-mobile waves-effect waves-light">
                        <i class="fe-menu"></i>
                    </button>
                </li>
            </ul>
            <div class="clearfix"></div>
        </div>
    </div>
    <!-- end Topbar -->

    <!-- ============================================================== -->
    <!-- Start Page Content here -->
    <!-- ============================================================== -->

    <div class="content-page">
        <div class="content">

            <!-- Start Content-->
            <div class="container-fluid">

                <!-- start page title -->
                @yield('page-header')
                <!-- end page title -->

                @yield('content')

            </div> <!-- container -->

        </div> <!-- content -->

        <!-- Footer Start -->
        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6">
                        <script>document.write(new Date().getFullYear())</script> &copy; A web-application on student's academic advising
                    </div>
                </div>
            </div>
        </footer>
        <!-- end Footer -->
        <form id="logout" action="{{ route('logout') }}" method="post">@csrf</form>
    </div>

    <!-- ============================================================== -->
    <!-- End Page content -->
    <!-- ============================================================== -->
</div>
<!-- END wrapper -->

<!-- Vendor js -->
<script src="{{ URL::asset('assets/js/vendor.min.js') }}"></script>

<!-- App js -->
<script src="{{ URL::asset('assets/js/app.min.js') }}"></script>
{{-- <script src="{{ URL::asset('assets/js/pusher.min.js') }}"></script> --}}
<script src="https://js.pusher.com/7.0/pusher.min.js"></script>

<script>

    var notif_url = "{{ route('teacherNotifications') }}";
    var profile_id = {{ session('profile_id') }};
    $(document).ready(function () {
        notifCount();
        populateNotif();
        populateMsg();
        MsgCount();
    });

    $('.logout').click(function(){
        $('#logout').submit();
    });

    var pusher = new Pusher('c7c292fecf19b1d3358d', {
        cluster: 'ap1'
    });
    
    var channel = pusher.subscribe('events');
    channel.bind('event-chat', function(data) {
        notifCount();
        populateNotif();
        populateMsg();
        MsgCount();
    });

    function notifCount() {
        $.ajax({
            url: "{{ route('notifCount') }}",
            type: 'GET',
            dataType: 'json',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function(data) {
                if (data != 0) {
                    $('.notif-count').html('<span class="badge badge-danger rounded-circle noti-icon-badge">'+data+'</span>');
                } else {
                    $('.notif-count').html('');
                }
            },
            error : function(request, status, error) {
                // swal("Oops!", "Seems like there is an error. Please try again", "error");
            }
        });
    }

    function populateNotif() {
        $.ajax({
            url: "{{ route('getNotif') }}",
            type: 'GET',
            dataType: 'json',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function(data) {
                if (data != '') {
                    $('.noti-scroll').remove();
                    $.each( data, function( key, value ) {
                        var active = value.is_read == 0 ? 'active' : '';
                        var img = '';
                        if (value.avatar != null) {
                            img = '{{ URL::asset('assets/images/user_avatars/') }}/'+value.sender_id+'/'+value.avatar;
                        } else {
                            img = '../assets/images/user.png';
                        }
                    
                        $('.noti').append('<div class="noti-scroll" data-simplebar><a href="{{ route('teacherNotifications') }}" onclick="markAsRead('+value.msg_id+')" class="dropdown-item notify-item '+active+'"> ' +
                                '<div class="notify-icon"> ' +
                                '<img src="'+img+'" class="img-fluid rounded-circle" /> ' +
                                '</div> <p class="notify-details">'+value.sender_name+'</p> ' +
                                '<p class="text-muted mb-0 user-msg"> ' +
                                '<small class="sp-line-2">'+value.message+'</small> </p> </a></div>');
                    });
                } else {
                    $('.noti-scroll').remove();
                }
            }
        });
    }

    function populateMsg() {
        $.ajax({
            url: "{{ route('teacherChatList') }}",
            type: 'GET',
            dataType: 'json',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function(data) {
                if (data != '') {
                    $('.msg-scroll').remove();
                    $.each( data, function( key, value ) {
                        var active = '';
                        var img = '';
                        var you = value.reply_last_sender == profile_id ? 'You: ' : '';
                        var cnt = '';

                        if (value.reply_last_sender == profile_id) {
                            cnt = ''
                            active = '';
                        } else if (value.unread == 0) {
                            cnt = ''
                            active = '';
                        } else {
                            cnt = value.unread;
                            active = 'bg-light';
                        }
                        var img = '';
                        if (value.sender_avatar != '') {
                            img = '{{ URL::asset('assets/images/user_avatars/') }}/'+value.sender_id+'/'+value.sender_avatar;
                        } else {
                            img = '../assets/images/user.png';
                        }
                    
                        $('.msg').append('<div class="noti-scroll msg-scroll" data-simplebar><a href="{{ route('teacherChat') }}" onclick="markAsRead('+value.parent_id+')" class="dropdown-item notify-item '+active+'"> ' +
                                '<div class="notify-icon"> ' +
                                '<img src="'+img+'" class="img-fluid rounded-circle" /> ' +
                                '</div> <p class="notify-details">'+value.sender_name+'</p> ' +
                                '<p class="text-muted mb-0 user-msg"> ' +
                                '<small class="sp-line-2">'+you+' '+value.message+'</small> </p> </a></div>');
                    });
                } else {
                    $('.msg-scroll').remove();
                }
            }
        });
    }

    function MsgCount() {
        $.ajax({
            url: "{{ route('chatCount') }}",
            type: 'GET',
            dataType: 'json',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function(data) {
                if (data != 0) {
                    $('.msg-count').html('<span class="badge badge-danger rounded-circle noti-icon-badge">'+data+'</span>');
                } else {
                    $('.msg-count').html('');
                }
            },
            error : function(request, status, error) {
                // swal("Oops!", "Seems like there is an error. Please try again", "error");
            }
        });
    }

    function clearAll() {
        $.ajax({
            url: "{{ route('notifDeleteAll') }}",
            type: 'DELETE',
            dataType: 'json',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function(data) {
                if (data.message == 'success') {
                    notifCount();
                    populateNotif();
                } 
            }
        });
    }
</script>

@yield('js')

</body>
</html>