@extends('teachers.teacherTemplate')

@section('title', 'Teacher | Notifications')

@section('css')
    <link href="{{ URL::asset('assets/libs/bootstrap-table/bootstrap-table.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ URL::asset('assets/libs/parsleyjs/css/parsley.css') }}">
@endsection

@section('page-header')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Notifications</h4>
            </div>
        </div>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="card-box">
            <table class="table table-borderless" id="notif-table" cellspacing="0">
                @foreach($data as $row)
                    <tr class="@if ($row->is_read == 0) unread @endif">
                        <td width="30px">
                            <div class="chat-avatar">
                                @if (!empty($row->avatar))
                                    <img src="{{ URL::asset('assets/images/user_avatars/'.$row->sender_id.'/'.$row->avatar.'') }}" class="avatar-md rounded">
                                @else    
                                    <img src="../assets/images/user.png" alt="user-image" class="avatar-md rounded">
                                @endif 
                            </div>
                        </td>
                        <td>
                            <br>
                            <p>{{ $row->message }}</p>
                        </td>
                        <td class="tdBtn">
                            <br>
                            @if ($row->is_read == 0)
                                <!-- Accept Buttons -->
                                <button class="btn btn-primary hidden accept-loading-{{ $row->notif_id }}" type="button" disabled="">
                                    <span class="spinner-border spinner-border-sm mr-1" role="status" aria-hidden="true"></span>
                                    Loading...
                                </button>
                                <button type="button" class="btn btn-primary accept-{{ $row->notif_id }} waves-effect waves-light" onclick="accept({{ $row->notif_id }}, {{ $row->event_id  }})">Accept</button>

                                <!-- Decline Buttons -->
                                <button type="button" class="btn btn-dark decline-{{ $row->notif_id }} waves-effect waves-light" onclick="decline({{ $row->notif_id }}, {{ $row->event_id  }})">Decline</button>
                                <button class="btn btn-dark hidden decline-loading-{{ $row->notif_id }}" type="button" disabled="">
                                    <span class="spinner-border spinner-border-sm mr-1" role="status" aria-hidden="true"></span>
                                    Loading...
                                </button>
                            @else
                                <button type="button" class="btn btn-dark delete-{{ $row->notif_id }} waves-effect waves-light" onclick="deleteNotif({{ $row->notif_id }})">Delete</button>
                                <button class="btn btn-dark hidden delete-loading-{{ $row->notif_id }}" type="button" disabled="">
                                    <span class="spinner-border spinner-border-sm mr-1" role="status" aria-hidden="true"></span>
                                    Loading...
                                </button>
                            @endif
                            
                        </td>
                    </tr>
                @endforeach
            </table>
        </div> <!-- end card-box-->
    </div> <!-- end col-->
</div>

<div id="reason-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel" style="display: none;" aria-modal="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <h5>Kindly tell us the reason why are you declining this appointment</h6>
                <form id="reason">   
                    <textarea rows="10" class="form-control user_reason" data-parsley-errors-container="#details-errors" required></textarea>
                    <input type="hidden" class="notif_id">
                    <input type="hidden" class="event_id">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary saveReason">Save changes</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- end row-->
@endsection

@section('js')
    <script src="{{ URL::asset('assets/libs/bootstrap-table/bootstrap-table.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/parsleyjs/js/parsley.min.js') }}"></script>

    <script>
        var accept_url      = "{{ route('teacherNotificationAccept') }}";
        var notif_del_url   = "{{ route('notifDelete') }}";

        function accept(notif_id, event_id) {
            $('.accept-'+notif_id).addClass('hidden');
            $('.accept-loading-'+notif_id).removeClass('hidden');
            $.ajax({
                url: accept_url,
                type: 'POST',
                data: {'notif_id' : notif_id, 'event_id' : event_id, 'status' : 'approved'},
                dataType: 'json',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function(data) {
                    if (data.message == 'success') {
                        swal({
                            title: "Success!",
                            text: "You accepted an appointment",
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

        function decline(notif_id, event_id) {
            //$('.decline-'+notif_id).addClass('hidden');
            //$('.decline-loading-'+notif_id).removeClass('hidden');
            $('#reason-modal').modal({
                backdrop: 'static',
                keyboard: false
            }, 'show');

            $('.notif_id').val(notif_id);
            $('.event_id').val(event_id);
        }

        $('.saveReason').click(function(){
            if ($('#reason').parsley().validate()) {
                $('#reason-modal').modal('toggle');
                var notif_id = $('.notif_id').val();
                var event_id = $('.event_id').val();
                $('.decline-'+notif_id).addClass('hidden');
                $('.decline-loading-'+notif_id).removeClass('hidden');
                
                $.ajax({
                url: accept_url,
                type: 'POST',
                data: {
                        'notif_id' : notif_id, 
                        'event_id' : event_id, 
                        'status' : 'declined', 
                        'reason' : $('.user_reason').val()
                    },
                dataType: 'json',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function(data) {
                    if (data.message == 'success') {
                        swal({
                            title: "Success!",
                            text: "You declined an appointment",
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

        function deleteNotif(notif_id) {
            $('.delete-'+notif_id).addClass('hidden');
            $('.delete-loading-'+notif_id).removeClass('hidden');
            $.ajax({
                url: notif_del_url,
                type: 'DELETE',
                data: {'notif_id' : notif_id},
                dataType: 'json',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function(data) {
                    if (data.message == 'success') {
                        swal({
                            title: "Success!",
                            text: "You deleted a notification",
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
    </script>
@endsection