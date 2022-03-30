@extends('teachers.teacherTemplate')

@section('title', 'Teacher | Dashboard')

@section('css')
    <link href="{{ URL::asset('assets/libs/flatpickr/flatpickr.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ URL::asset('assets/libs/parsleyjs/css/parsley.css') }}">
    <link href="{{ URL::asset('assets/libs/fullcalendar/main.css') }}" rel="stylesheet" type="text/css" />

    <style>
        .zoom {
            cursor: pointer;
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
<div class="row">
    <div class="col-12">

        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div id="calendar"></div>
                    </div> <!-- end col -->

                </div>  <!-- end row -->
            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div>
    <!-- end col-12 -->
</div> <!-- end row -->
    <div id="info-alert-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body p-4">
                    <div class="text-center">
                        <i class="dripicons-information h1 text-info"></i>
                        <h4 class="mt-2">Heads up!</h4>
                        <p class="mt-3 event_title"></p>
                        <p class="mt-3">Zoom Link : <a href="#" target="_blank" class="zoom_link"><label class="zoom"></label></a></p>
                    </div>
                </div>
                <p class="closedBtn"></p>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div id="schedule-alert" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body p-4">
                    <div class="text-center">
                        <i class="dripicons-information h1 text-info"></i>
                        <h4 class="mt-2">Reminder!</h4>
                        <p>You don't have any schedule for this week. Please set up your schedule.</p>
                        <a href="{{ route('teacherSchedule') }}" class="btn btn-info my-2">Setup Schedule</a>
                    </div>
                </div>
                <p class="closedBtn"></p>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <input type="hidden" class="schedule-cnt" value="{{ $schedule_cnt }}">
@endsection

@section('js')
    <script src="{{ URL::asset('assets/libs/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/parsleyjs/js/parsley.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/fullcalendar/main.js') }}"></script>

    <script>
        var initial_date = "{{ $initial_date }}";
        var list_url = "{{ route('teacherEventList') }}";
        var closed_url = "{{ route('closedEvent') }}";
        var schedule_cnt = $('.schedule-cnt').val();
        
        if (schedule_cnt == 0) {
            showModal();
        }

        $(function () {
            var App = {
                //baseUrl : window.location.protocol + '//' + window.location.host + '/admin/reports/',
                csrfToken : $('meta[name="csrf-token"]').attr('content'),

                init: function () {
                    this.setElements();
                    this.bindEvents();
                    App.initCalendar(initial_date);
                },

                setElements: function () {
                    $("#from-datepicker").flatpickr();
                    $("#to-datepicker").flatpickr();
                    this.$save = $('#save');
                },

                bindEvents: function () {
                    this.$save.on('click', this.save);
                },

                initCalendar: function(initial_date) {
                    var calendarEl = document.getElementById('calendar');
                    var calendar = new FullCalendar.Calendar(calendarEl, {
                        headerToolbar: {
                            left: 'prevYear,prev,next,nextYear today',
                            center: 'title',
                            right: 'dayGridMonth,dayGridWeek,dayGridDay'
                        },
                        initialDate: ''+initial_date+'',
                        navLinks: true,
                        editable: true,
                        dayMaxEvents: true,
                        eventColor: '#f3993e',
                        eventSources: [
                            {
                                url: list_url,
                                method: 'GET',
                                failure: function() {
                                    alert('there was an error while fetching events!');
                                },
                                textColor: '#ffffff'
                            }
                        ],
                        eventClick:  function(info) {
                            $('.event_title').html(info.event.title);
                            $('.zoom').html(info.event.id);
                            $('.zoom_link').attr("href", info.event.id);
                            $('.closedBtn').html('<button type="button" id="saveBtn" class="btn btn-primary waves-effect waves-light m-1" onclick="closed('+info.event.textColor+')"> Closed Event</button>');
                            $('#info-alert-modal').modal();
                        },
                    });

                    calendar.render();
                }
            };

            App.init();
        });

        function closed(id) {
            $('#preloader').removeClass('hidden');
            $('#status').removeClass('hidden');

            $.ajax({
                url: closed_url,
                type: 'POST',
                data: {'event_id' : id},
                dataType: 'json',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function(data) {
                    if (data.message == 'success') {
                        $('#preloader').addClass('hidden');
                        $('#status').addClass('hidden');
                        swal({
                            title: "Success!",
                            text: "You successfully closed an event.",
                            type: "success",
                            confirmButtonColor:"#f2993e",
                        }).then(function() {
                            location.reload();
                        });
                    }
                },
                error : function(request, status, error) {
                    swal("Oops!", "Seems like there is an error. Please try again", "error");
                }
            });
        }

        function showModal()
        {
            $('#schedule-alert').modal({
                backdrop: 'static',
                keyboard: false
            }, 'show');
        }
    </script>
@endsection