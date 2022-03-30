@extends('teachers.teacherTemplate')

@section('title', 'Teacher | Chat')

@section('css')
<link href="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .chat_div { 
            margin-top: 2%;
        }
        .media {
            margin-bottom: 3%;
        }
    </style>
@endsection

@section('page-header')
   
@endsection

@section('content')
<div class="row chat_div">
    <!-- start chat users-->
    <div class="col-xl-3 col-lg-4">
        <div class="card">
            <div class="card-body">
                <!-- start search box -->
                <!-- <form class="search-bar mb-3">
                    <div class="position-relative">
                        <input type="text" class="form-control form-control-light" placeholder="People, groups & messages...">
                        <span class="mdi mdi-magnify"></span>
                    </div>
                </form> -->
                <!-- end search box -->

                <h6 class="font-13 text-muted text-uppercase mb-2">Chats</h6>

                <!-- users -->
                <div class="row">
                    <div class="col">
                        <div class="chat-list" style="max-height: 540px; min-height: 600px;">
                            

                        </div> <!-- end slimscroll-->
                    </div> <!-- End col -->
                </div>
                <!-- end users -->
            </div> <!-- end card-body-->
        </div> <!-- end card-->
    </div>
    <!-- end chat users-->

    <!-- chat area -->
    <div class="col-xl-9 col-lg-8">
        <div class="div_data">
           @include('teachers.chat_page')
        </div>
    </div>
    <!-- end chat area-->

</div> <!-- end row-->
@endsection

@section('js')
<script src="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <script>
        var profile_id = {{ session('profile_id') }};
        $(document).ready(function () {
            populateChatList();
        });

        var fetch_url = "{{ route('teacherChatData') }}";

        //Pusher.logToConsole = true;
        
        function populateChatList() {
            $('.chat-list-link').remove();
            $.ajax({
            url: "{{ route('teacherChatList') }}",
            type: 'GET',
            dataType: 'json',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function(data) {
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

                        if (value.sender_id == null) {
                            img = '{{ URL::asset('assets/images/user.png') }}';
                        } else {
                            img = '{{ URL::asset('assets/images/user_avatars/') }}/'+value.sender_id+'/'+value.sender_avatar;
                        }
                        $('.chat-list').append(
                            '<a href="javascript:void(0);" onclick="fetch_data('+value.parent_id+', '+value.sender_id+')" class="text-body chat-list-link">' + 
                                '<div class="media p-2 ">' +
                                    '<img src="'+img+'" class="mr-2 rounded-circle" height="42" alt="Brandon Smith" />' + 
                                    '<div class="media-body">' +
                                        '<h5 class="mt-0 mb-0 font-14">' +
                                            '<span class="float-right text-muted font-weight-normal font-12">'+value.created_at+'</span>' +
                                            ''+value.sender_name+'' +
                                            '</h5>' +
                                        '<p class="mt-1 mb-0 text-muted font-14">' +
                                            '<span class="w-25 float-right text-right"><span class="badge badge-soft-danger">'+cnt+'</span></span>' +
                                            '<span class="w-75">'+you+' '+value.message+'</span>' +
                                        '</p>' +
                                    '</div>' +
                                '</div>' +
                            '</a>'
                        );
                    });
            },
            error : function(request, status, error) {
                // swal("Oops!", "Seems like there is an error. Please try again", "error");
            }
        });
        }

        function fetch_data(parent_id, sender_id)
        {
            populateChatList();
            $('#preloader').removeClass('hidden');
            $('#status').removeClass('hidden');
            localStorage.setItem('parent_id', parent_id);
            $.ajax({
                url: fetch_url,
                data: {'parent_id': parent_id, 'sender_id' : sender_id},
                success:function(data)
                {
                    $('.div_data').html(data);
                    $('#preloader').addClass('hidden');
                    $('#status').addClass('hidden');
                }
            });
        }

        var pusher = new Pusher('c7c292fecf19b1d3358d', {
            cluster: 'ap1'
        });
        //Pusher.logToConsole = true;
        var channel = pusher.subscribe('student-reply');
        channel.bind('student-reply-chat', function(data) {
            populateChatList();
            var parent_id = localStorage.getItem('parent_id')
            $.each( data, function( key, value ) {
                
                if (value.parent_id == parent_id) {
                    $('.conversation-list').append('<li class="clearfix">' +
                                '<div class="chat-avatar">' +
                                    '<img src="../assets/images/user_avatars/'+value.sender_id+'/'+value.receiver_avatar+'" class="mr-2 rounded" height="40"> ' +
                                    '<p class="time">'+value.created_at+'</p>' +
                                '</div>' +
                                '<div class="conversation-text">' +
                                    '<div class="ctext-wrap">' +
                                        '<p>' +
                                            ''+value.message+'' +
                                        '</p>' +
                                    '</div>' +
                                '</div>' +
                            '</li>');
                }
            });
            $('.conversation-list').scrollTop($('.conversation-list')[0].scrollHeight);
        });
        
    </script>
@endsection