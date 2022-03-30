@if (!empty($data))
<div class="card">
    <input type="hidden" class="avatar" value="{{ URL::asset('assets/images/user_avatars/') }}/{{ session('profile_id') }}/{{ session('avatar') }}">
    <div class="card-body py-2 px-3 border-bottom border-light">
        <div class="media py-1">
            @if ($sender->avatar != null)
                <img src="{{ URL::asset('assets/images/user_avatars/') }}/{{ $sender->profile_id }}/{{ $sender->avatar }}" class="mr-2 rounded-circle" height="36">
            @else
                <img src="../assets/images/user.png" class="mr-2 rounded-circle" height="36"> 
            @endif
            
            <div class="media-body">
                <h5 class="mt-0 mb-0 font-15">
                    <a href="contacts-profile.html" class="text-reset">{{ $sender->first_name.' '.$sender->last_name }}</a>
                </h5>
                <p class="mt-1 mb-0 text-muted font-12">
                    {{-- <small class="mdi mdi-circle text-success"></small> Online --}}
                </p>
            </div>
            <div>
                <a href="javascript: void(0);" onclick="deleteChat({{ $parent_id }})" class="text-reset font-19 py-1 px-2 d-inline-block" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete Chat">
                    <i class="fe-trash-2"></i>
                </a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <ul class="conversation-list" style="max-height: 420px; min-height: 420px; overflow: auto;">

            @foreach ($data as $row)
                @if ($row->sender_id != session('profile_id'))
                    <li class="clearfix">
                        <div class="chat-avatar">
                            @if ($sender->avatar != null)
                                <img src="{{ URL::asset('assets/images/user_avatars/') }}/{{ $sender->profile_id }}/{{ $sender->avatar }}" class="rounded" height="40">
                            @else
                                <img src="../assets/images/user.png" class="mr-2 rounded-circle" height="40"> 
                            @endif
                            <p class="time">{{ date('h:i A', strtotime($row->created_at)) }}</p>
                        </div>
                        <div class="conversation-text">
                            <div class="ctext-wrap">
                                <p>
                                    {{ $row->message }}
                                </p>
                            </div>
                        </div>
                    </li>
                @endif
            @endforeach

            @foreach ($replies as $reply)
                @if ($reply->sender_id == session('profile_id'))
                    <li class="clearfix odd">
                        <div class="chat-avatar">
                            <img src="{{ URL::asset('assets/images/user_avatars/') }}/{{ session('profile_id') }}/{{ session('avatar') }}" class="rounded" />
                            <p class="time">{{ date('h:i A', strtotime($reply->created_at)) }}</p>
                        </div>
                        <div class="conversation-text">
                            <div class="ctext-wrap">
                                <p>
                                    {{ $reply->message }}
                                </p>
                            </div>
                        </div>
                    </li>
                @else
                    <li class="clearfix">
                        <div class="chat-avatar">
                            @if ($sender->avatar != null)
                                <img src="{{ URL::asset('assets/images/user_avatars/') }}/{{ $sender->profile_id }}/{{ $sender->avatar }}" class="rounded" height="40">
                            @else
                                <img src="../assets/images/user.png" class="rounded" height="40"> 
                            @endif
                            <p class="time">{{ date('h:i A', strtotime($reply->created_at)) }}</p>
                        </div>
                        <div class="conversation-text">
                            <div class="ctext-wrap">
                                <p>
                                    {{ $reply->message }}
                                </p>
                            </div>
                        </div>
                    </li>
                @endif
            @endforeach
        </ul>

        <div class="row">
            <div class="col">
                <div class="mt-2 bg-light p-3 rounded">
                    <form class="needs-validation" novalidate="" name="chat-form" id="chat-form">
                        <div class="row">
                            <div class="col mb-2 mb-sm-0">
                                <input type="text" class="form-control border-0 chat-msg" name="message" placeholder="Enter your text" required="">
                                <div class="invalid-feedback">
                                    Please enter your messsage
                                </div>
                                <input type="hidden" name="parent_id" value="{{ $parent_id }}">
                                <input type="hidden" name="receiver_id" value="{{ $sender->profile_id }}">
                                <input type="hidden" name="receiver_avatar" value="{{ session('avatar') }}">
                                <input type="hidden" name="sender_id" value="{{ session('profile_id') }}">
                                {{-- <input type="hidden" class="date" value="{{ date('h:i A', strtotime(\Carbon\Carbon::now())) }}"> --}}
                            </div>
                            <div class="col-sm-auto">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary chat-send btn-block" onclick="send()"><i class='fe-send'></i></button>
                                    <button class="btn btn-primary chat-loading hidden" type="button" disabled="">
                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span><span class="sr-only">Loading...</span>
                                    </button>
                                </div>
                            </div> <!-- end col -->
                        </div> <!-- end row-->
                    </form>
                </div> 
            </div> <!-- end col-->
        </div>
        <!-- end row -->
    </div> <!-- end card-body -->
</div> <!-- end card -->
@endif

<script>
    function send() {
        var date = "{{ date('h:i A', strtotime(\Carbon\Carbon::now())) }}";
        $('.chat-send').addClass('hidden');
        $('.chat-loading').removeClass('hidden');
        $.ajax({
            url: "{{ route('reply') }}",
            type: 'POST',
            data: $("#chat-form").serialize(),
            dataType: 'json',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function(data) {
                if (data.msg == 'success') {
                    $('.chat-send').removeClass('hidden');
                    $('.chat-loading').addClass('hidden');
                    populateChatList();
                    var a = $('.avatar').val();
                    $('.conversation-list').append('<li class="clearfix odd">' +
                            '<div class="chat-avatar">' +
                                '<img src="'+a+'" class="mr-2 rounded" height="40"> ' +
                                '<p class="time">'+date+'</p>' +
                            '</div>' +
                            '<div class="conversation-text">' +
                                '<div class="ctext-wrap">' +
                                    '<p>' +
                                        ''+$('.chat-msg').val()+'' +
                                    '</p>' +
                                '</div>' +
                            '</div>' +
                        '</li>');

                    $('.chat-msg').val('');    
                    $('.conversation-list').scrollTop($('.conversation-list')[0].scrollHeight);
                }
            },
            error : function(request, status, error) {
                //swal("Oops!", "Seems like there is an error. Please try again", "error");
            }
        });
    }

    function deleteChat(parent_id)
    {
        Swal.fire({
            title:"Are you sure",
            text: "You want to delete this conversation?",
            type: "warning",
            showCancelButton:!0,
            confirmButtonColor:"#f2993e",
            cancelButtonColor:"#323a46",
            confirmButtonText:"Yes, delete it!"
        }).then(function(t){
            if (t.value == true) {
                $('#preloader').removeClass('hidden');
                $('#status').removeClass('hidden');
                $.ajax({
                    url: "{{ route('chatDelete') }}",
                    type: 'POST',
                    data: {'parent_id' : parent_id},
                    dataType: 'json',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    success: function(data) {
                        $('#preloader').addClass('hidden');
                        $('#status').addClass('hidden');
                        if (data.message == 'success') {
                            swal({
                                title: "Success!",
                                text: "Successfully deleted a conversation",
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
            
        })
    }
</script>