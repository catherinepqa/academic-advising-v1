@extends('students.template')

@section('title', 'Student | Notifications')

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
            <table class="table table-borderless"  id="notif-table" cellspacing="0">
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
                        <td class="tdBtn" width="20%">
                            <br>
                            @if ($row->is_read == 0)
                                <!-- Accept Buttons -->
                                <button class="btn btn-primary hidden accept-loading-{{ $row->notif_if }}" type="button" disabled="">
                                    <span class="spinner-border spinner-border-sm mr-1" role="status" aria-hidden="true"></span>
                                    Loading...
                                </button>
                                <button type="button" class="btn btn-primary accept-{{ $row->notif_if }} waves-effect waves-light" onclick="read({{ $row->notif_id }})">Mark as read</button>

                                <!-- Decline Buttons -->
                                <button type="button" class="btn btn-dark decline waves-effect waves-light" onclick="deleteNotif({{ $row->notif_id }})">Delete</button>
                                <button class="btn btn-dark hidden decline-loading" type="button" disabled="">
                                    <span class="spinner-border spinner-border-sm mr-1" role="status" aria-hidden="true"></span>
                                    Loading...
                                </button>
                            @else
                                <button type="button" class="btn btn-dark delete-{{ $row->notif_if }} waves-effect waves-light" onclick="deleteNotif({{ $row->notif_id }})">Delete</button>
                                <button class="btn btn-dark hidden delete-loading-{{ $row->notif_if }}" type="button" disabled="">
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
<!-- end row-->
@endsection

@section('js')
    <script src="{{ URL::asset('assets/libs/bootstrap-table/bootstrap-table.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/parsleyjs/js/parsley.min.js') }}"></script>

    <script>
        var read_url        = "{{ route('notifRead') }}";
        var notif_del_url   = "{{ route('notifDelete') }}";

        function read(notif_id) {
            $('.accept').addClass('hidden');
            $('.accept-loading').removeClass('hidden');
            $.ajax({
                url: read_url,
                type: 'PUT',
                data: {'notif_id' : notif_id},
                dataType: 'json',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function(data) {
                    if (data.message == 'success') {
                        location.reload();
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

        function deleteNotif(notif_id) {
            $('.delete').addClass('hidden');
            $('.delete-loading').removeClass('hidden');
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