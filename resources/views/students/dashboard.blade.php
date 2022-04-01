@extends('students.template')

@section('title', 'Student | Dashboard')

@section('css')
    <link href="{{ URL::asset('assets/libs/flatpickr/flatpickr.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ URL::asset('assets/libs/parsleyjs/css/parsley.css') }}">

    <style>
        #addForm h5 {
            text-align: center;
        }
        #addForm .time-list {
            margin-left: 1%;
            padding: 5px;
        }
        #addForm .time-list:hover {
            cursor: pointer;
            background-color: #f3993e;
            color: #fff;
        }

        .selected-time {
            background-color: #f3993e;
            color: #fff;
        }
    </style>
@endsection

@section('page-header')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Dashboard</h4>
            </div>
        </div>
    </div>
@endsection

@section('content')
<!--<div class="row">
     <div class="col-12">
        <div class="card-box">
            <div class="row">
                <div class="col-lg-10">
                    <div class="form-group">
                        <label for="inputPassword2" class="sr-only">Search</label>
                        <input type="search" class="form-control" id="inputPassword2" placeholder="Search...">
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-group">
                        <button type="button" class="btn btn-primary waves-effect waves-light" style="width: 100%">Search</button>
                    </div>
                </div>
            </div> 
        </div> 
    </div>
</div> -->
<input type="hidden" id="monday" value="{{ $monday }}">
<input type="hidden" id="friday" value="{{ $friday }}">
<div class="row">
    @foreach($teachers as $teacher)
        <div class="col-lg-3">
            <div class="text-center card-box">
                <div class="pt-2 pb-2">
                    @if (!empty($teacher->avatar))
                        <img src="{{ URL::asset('assets/images/user_avatars/'.$teacher->profile_id.'/'.$teacher->avatar.'') }}" class="rounded-circle img-thumbnail avatar-xl" alt="profile-image">
                    @else
                        <img src="{{ URL::asset('assets/images/user.png') }}" class="rounded-circle img-thumbnail avatar-xl" alt="profile-image">
                    @endif
                    

                    <h4 class="mt-3"><a href="extras-profile.html" class="text-dark">{{ $teacher->first_name.' '.$teacher->last_name }}</a></h4>
                    <p class="text-muted">{{ $teacher->email }} <span> | </span> <span> {{ $teacher->contact_number }} </span></p>

                    <input type="hidden" class="teacher_name_{{ $teacher->profile_id }}" value="{{ $teacher->first_name.' '.$teacher->last_name }}">
                    <input type="hidden" class="teacher_email_{{ $teacher->profile_id }}" value="{{ $teacher->email }}">

                    <button type="button" class="btn btn-primary btn-sm waves-effect waves-light" onclick="message({{ $teacher->profile_id }})">Message</button>
                    <button type="button" class="btn btn-dark btn-sm waves-effect" onclick="bookAppointment({{ $teacher->profile_id }})" >Book an Appointment</button>

                </div> <!-- end .padding -->
            </div> <!-- end card-box-->
        </div> <!-- end col -->
    @endforeach
</div>

<div id="book-appointment" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Make an appointment to <label class="faculty"></label></h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <form id="addForm">
                    <input type="hidden" id="teacher_id" name="teacher_id">
                    <input type="hidden" id="schedule_id" name="schedule_id">
                    <h5 class="bg-light p-2 mt-0 no_data">No data Available<h5>
                    @foreach ($dates as $dt)
                        <h5 class="bg-light p-2 mt-0 sched-{{$dt['day']}} hidden">Available time for {{ $dt['day'] }} {{ date("F j, Y", strtotime($dt['date'])) }}<h5>
                        <div class="sched-list-{{$dt['day']}}"></div>
                    @endforeach
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark waves-effect" data-dismiss="modal">Close</button>
                <button id="saveBtn" type="button" class="btn btn-primary waves-effect waves-light">Submit</button>
            </div>
        </div>
    </div>
</div><!-- /.modal -->

<div id="message-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Sent a message to <label class="teacher_name"></label></h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <form id="messageForm">
                    <div class="form-group">
                        <label for="field-1" class="control-label">Message</label>
                        <textarea class="form-control message" name="message" rows="15" required data-parsley-errors-container="#details-errors"></textarea>
                        <input type="hidden" class="teacher_id" name="receiver_id" value="">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark waves-effect" data-dismiss="modal">Close</button>
                <button id="sentBtn" type="button" class="btn btn-primary waves-effect waves-light">Send</button>
            </div>
        </div>
    </div>
</div><!-- /.modal -->

@endsection

@section('js')
    <script src="{{ URL::asset('assets/libs/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/parsleyjs/js/parsley.min.js') }}"></script>
    <script>
        $("#datetime-datepicker").flatpickr({
            enableTime:!0,
            dateFormat:"Y-m-d H:i"
        });
        var add_url = "{{ route('appointment') }}";
        var send_url = "{{ route('send') }}";

        function bookAppointment(id) {
            $('#book-appointment').modal({
                backdrop: 'static',
                keyboard: false
            }, 'show');
            $('.faculty').html($('.teacher_name_'+id).val());
            $('#teacher_id').val(id);
            $.ajax({
                url: "{{ route('teacherScheduleList') }}",
                type: 'GET',
                data: {'monday' : $('#monday').val(), 'friday' : $('#friday').val(), 'teacher_id' : id},
                dataType: 'json',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function(data) {
                    $('.time-list').remove();
                    $.each( data, function( key, value ) {
                        $('.no_data').addClass('hidden');
                        $('.sched-'+value.day).removeClass('hidden');
                        $('.sched-list-'+value.day).append('<label class="time-list select-time-'+value.schedule_id+'" onclick="selectTime('+value.schedule_id+')" data-day="'+value.day+'">'+
                                        ''+value.start+' to '+value.end+'' +
                                        '</label>');
                    });
                },
                error : function(request, status, error) {
                    swal("Oops!", "Seems like there is an error. Please try again", "error");
                }
            });  
        }

        function selectTime(id) {
            var day = $('.select-time-'+id).data("day");
            $('.time-list').removeClass('selected-time');
            $('.select-time-'+id).addClass('selected-time');
            $('#schedule_id').val(id);
        }

        function message(id) {
            $('#message-modal').modal({
                backdrop: 'static',
                keyboard: false
            }, 'show');
            var teacher_name = $('.teacher_name_'+id).val();
            $('.teacher_name').html(teacher_name);
            $('.teacher_id').val(id);   
        }

        $('#saveBtn').click(function(){
            Swal.fire({
                title:"Are you sure",
                text: "You want to save this record?",
                type: "warning",
                showCancelButton:!0,
                confirmButtonColor:"#f2993e",
                cancelButtonColor:"#323a46",
                confirmButtonText:"Yes, save it!"
            }).then(function(t){
                $('#preloader').removeClass('hidden');
                $('#status').removeClass('hidden');
                if (t.value == true) {
                    $('#book-appointment').modal('toggle');
                    $.ajax({
                        url: add_url,
                        type: 'POST',
                        data: $("#addForm").serialize(),
                        dataType: 'json',
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        success: function(data) {
                            if (data.msg == 'success') {
                                $('#preloader').addClass('hidden');
                                $('#status').addClass('hidden');
                                swal({
                                    title: "Success!",
                                    text: "You successfully booked an appointment.",
                                    type: "success",
                                    confirmButtonColor:"#f2993e",
                                }, function () {
                                    //$('#title').val('');
                                    //$('.category').val('');
                                    //$('.category').removeClass('parsley-success');
                                });
                            }
                        },
                        error : function(request, status, error) {
                            swal("Oops!", "Seems like there is an error. Please try again", "error");
                        }
                    });
                }
            }) 
        });

        $('#sentBtn').click(function(){
            if ($('#messageForm').parsley().validate()) {
                    $('#message-modal').modal('toggle');
                    $.ajax({
                        url: send_url,
                        type: 'POST',
                        data: $("#messageForm").serialize(),
                        dataType: 'json',
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        success: function(data) {
                            if (data.msg == 'success') {
                                swal({
                                    title: "Success!",
                                    text: "You successfully sent a message.",
                                    type: "success",
                                    confirmButtonColor:"#f2993e",
                                }, function () {
                                    //$('#title').val('');
                                    //$('.category').val('');
                                    //$('.category').removeClass('parsley-success');
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