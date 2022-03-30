@extends('teachers.teacherTemplate')

@section('title', 'Teacher | My Account')

@section('css')
    <link href="{{ URL::asset('assets/libs/flatpickr/flatpickr.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ URL::asset('assets/libs/parsleyjs/css/parsley.css') }}">
    <link href="{{ URL::asset('assets/libs/dropify/css/dropify.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('page-header')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">My Account</h4>
            </div>
        </div>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form id="form" method="post" novalidate enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-xl-6">
                            <div class="row">
                                <div class="col-lg-6">
                                    <!-- Date View -->
                                    <div class="form-group">
                                        <label>First Name</label>
                                        <input type="text" class="form-control" placeholder="Enter First Name" 
                                        value="{{ $data->first_name }}" required name="first_name">
                                    </div>
                                </div>
    
                                <div class="col-lg-6">
                                    <!-- Date View -->
                                    <div class="form-group">
                                        <label>Last Name</label>
                                        <input type="text" class="form-control" placeholder="Enter Last Name" 
                                        value="{{ $data->last_name }}" required name="last_name">
                                    </div>
                                </div>
                            </div>
    
                            <div class="row">
                                <div class="col-lg-6">
                                    <!-- Date View -->
                                    <div class="form-group">
                                        <label>Employee Number</label>
                                        <input type="text" class="form-control" placeholder="Enter Employee Number" 
                                        value="{{ $data->employee_number }}" name="employee_number">
                                    </div>
                                </div>
    
                                <div class="col-lg-6">
                                    <!-- Date View -->
                                    <div class="form-group">
                                        <label>Email Address</label>
                                        <input type="text" class="form-control" placeholder="Enter Email Address" 
                                        value="{{ $data->email }}" required name="email">
                                    </div>
                                </div>
                            </div>
    
                            <div class="row">
                                <div class="col-lg-6">
                                    <!-- Date View -->
                                    <div class="form-group">
                                        <label>Contact Number</label>
                                        <input type="text" class="form-control" placeholder="Enter Contact Number" 
                                        value="{{ $data->contact_number }}" name="contact_number">
                                    </div>
                                </div>
    
                                <div class="col-lg-6">
                                    <!-- Date View -->
                                    <div class="form-group">
                                        <label>Birthday</label>
                                        <input type="text" id="basic-datepicker" class="form-control" placeholder="Enter Birthday" 
                                        value="{{ $data->birthday }}" name="birthday">
                                    </div>
                                </div>
                            </div>
    
                            <div class="form-group">
                                <label for="project-overview">Address</label>
                                <textarea class="form-control" id="project-overview" rows="5" placeholder="Enter your complete address" name="address">{{ $data->address }}</textarea>
                            </div>
    
                        </div> <!-- end col-->
    
                        <div class="col-xl-6">
                            <div class="form-group mt-3 mt-xl-0">
                                <label for="projectname" class="mb-0">Avatar</label>
                                <p class="text-muted font-14"></p>
                                <input type="file" name="avatar" class="dropify" data-plugins="dropify" data-height="300" 
                                data-default-file="@if (!empty($data->avatar)){{ URL::asset('assets/images/user_avatars/'.$data->profile_id.'/'.$data->avatar.'') }}@endif" />
                                <input type="hidden" name="profile_id" value="{{ $data->profile_id }}" />
                                <input type="hidden" name="user_id" value="{{ $data->user_id }}" />
                                <input type="hidden" name="role" value="{{ $data->role }}" />
                            </div>
                        </div> <!-- end col-->
                    </div>
                </form>
                <!-- end row -->


                <div class="row mt-3">
                    <div class="col-12">
                        <button type="button" id="saveBtn" class="btn btn-primary waves-effect waves-light m-1"> Update</button>
                        <button type="button" id="changePass" class="btn btn-dark waves-effect waves-light m-1"> Change Password</button>
                    </div>
                </div>

            </div> <!-- end card-body -->
        </div> <!-- end card-->
    </div> <!-- end col-->
</div>
<!-- end row-->

<div id="change-pass" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Change Password</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <form id="changePassForm">
                    <div class="form-group">
                        <label>New Password</label>
                        <input type="password" class="form-control new_pass" placeholder="Enter New Password" required name="new_password">
                        <input type="hidden" class="user_id" name="user_id" value="{{ $data->user_id }}" />
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark waves-effect" data-dismiss="modal">Close</button>
                <button id="updateBtn" type="button" class="btn btn-primary waves-effect waves-light">Submit</button>
            </div>
        </div>
    </div>
</div><!-- /.modal -->
@endsection

@section('js')
    <script src="{{ URL::asset('assets/libs/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/parsleyjs/js/parsley.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/dropify/js/dropify.min.js') }}"></script>
    <script>
        var update_url = "{{ route('teacherProfileUpdate') }}";
        var change_pass_url = "{{ route('changePassword') }}";

        $('.dropify').dropify();
        $("#basic-datepicker").flatpickr();

        $('#saveBtn').click(function(){
            if ($('#form').parsley().validate()) {
                Swal.fire({
                    title:"Are you sure",
                    text: "You want to update this record?",
                    type: "warning",
                    showCancelButton:!0,
                    confirmButtonColor:"#f2993e",
                    cancelButtonColor:"#323a46",
                    confirmButtonText:"Yes, update it!"
                }).then(function(t){
                    if (t.value == true) {
                        var form = document.getElementById('form');
                        var formData = new FormData(form);
                        $.ajax({
                            url: update_url,
                            type: 'POST',
                            data: formData,
                            dataType: 'json',
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            success: function(data) {
                                if (data.message == 'success') {
                                    swal({
                                        title: "Success!",
                                        text: "You successfully updated your account",
                                        type: "success",
                                        confirmButtonColor:"#f2993e",
                                    }).then(function() {
                                        location.reload();
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
                            },
                            contentType: false,
                            processData: false,
                            cache: false
                        });
                    }
                    
                })
            }
        });

        $('#changePass').click(function(){
            $('#change-pass').modal({
                backdrop: 'static',
                keyboard: false
            }, 'show');
        });

        $('#updateBtn').click(function(){
            if ($('#changePassForm').parsley().validate()) {
                $.ajax({
                    url: change_pass_url,
                    type: 'POST',
                    data: $("#changePassForm").serialize(),
                    dataType: 'json',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    success: function(data) {
                        if (data.message == 'success') {
                            swal({
                                title: "Success!",
                                text: "You successfully updated your password",
                                type: "success",
                                confirmButtonColor:"#f2993e",
                            }).then(function() {
                                location.reload();
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
@endsection