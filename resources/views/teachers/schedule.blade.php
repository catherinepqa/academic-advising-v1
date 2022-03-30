@extends('teachers.teacherTemplate')

@section('title', 'Teacher | Schedule')

@section('css')
    <link href="{{ URL::asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('assets/libs/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('assets/libs/clockpicker/bootstrap-clockpicker.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ URL::asset('assets/libs/parsleyjs/css/parsley.css') }}">
    <link href="{{ URL::asset('assets/libs/flatpickr/flatpickr.min.css') }}" rel="stylesheet" type="text/css" />

    <style>
        #searchBtn {
            width: 100%;
            margin-top: 16%;
        }

        .badge {
            font-size: 13px;
        }

        .align-center {
            text-align: center;
        }
    </style>
@endsection

@section('page-header')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <button type="button" class="btn btn-primary waves-effect waves-light addBtn" onclick="addTime()">
                        <i class="fe-plus"></i>  Add Available Time
                    </button>
                </div>
                <h4 class="page-title">Set Your Schedule for this week {{ date("F j, Y", strtotime($monday)) }} to {{ date("F j, Y", strtotime($friday)) }}</h4>
            </div>
        </div>
    </div>
@endsection

@section('content')
<div class="row date">
    @foreach ($dates as $dt)
        <div class="col-lg-6 col-xl-2">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ date("F j, Y", strtotime($dt['date'])) }} </h5>
                    <label class="time-available">{{ $dt['day'] }} Time Availability</label>
                    @if (!empty($list))
                        @foreach ($list as $row)
                            @if ($dt['day'] == $row->day)
                                <div class="time-list @if ($row->status == 'Approved') time-list-approve @endif">
                                    {{ date('h:i A', strtotime($row->start_date_time)) }} to {{ date('h:i A', strtotime($row->end_date_time)) }}
                                    <a href="javascript:void(0)" class="remove" tabindex="-1" onclick="deleteSched({{$row->schedule_id}})" title="Remove">×</a>
                                </div>
                            @endif
                        @endforeach
                        
                    @endif
                </div>
            </div>
        </div>
    @endforeach
    
    <div class="col-lg-12">
        <div class="card-box pb-2">
            <h4 class="header-title mb-3">List of all schedule</h4>
            <div class="row">
                <div class="col-lg-5">
                    <div class="form-group">
                        <label>Start Date</label>
                        <input type="text" id="startDate" class="form-control" placeholder="Date and Time" value="{{ $startOfMonth }}">
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="form-group">
                        <label>End Date</label>
                        <input type="text" id="endDate" class="form-control" placeholder="Date and Time" value="{{ $endOfMonth }}">
                    </div>
                </div>
                <div class="col-lg-2">
                    <button id="searchBtn" type="button" class="btn btn-primary waves-effect waves-light">Search</button>
                </div>  
            </div>

            <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Day</th>
                        <th>Time</th>
                        <th>Duration</th>
                    </tr>
                </thead>
            
                <tbody></tbody>
            </table>
        </div> <!-- end card-box -->
    </div> 
</div>   

<div id="time-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add time of availability</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <form id="timeForm">
                    <input type="hidden" name="date" class="selected-date">
                    <input type="hidden" name="day" class="selected-day">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group mb-3">
                                <label>Select a date</label>
                                <select id="date-list" class="form-control" required>
                                    <option value="">Select</option>
                                    @foreach ($dates as $dt)
                                        <option value="{{ $dt['day'] }}">{{ date("F j, Y", strtotime($dt['date'])) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label>Select Start Time</label>
                                <div class="input-group clockpicker">
                                    <input type="text" class="form-control start_time" name="start_time" required data-parsley-errors-container="#details-errors">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="mdi mdi-clock-outline"></i></span>
                                    </div>
                                </div>
                                <div id="details-errors"></div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label>Select End Time</label>
                                <div class="input-group clockpicker">
                                    <input type="text" class="form-control end_time" name="end_time" required data-parsley-errors-container="#details-errors1">
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="mdi mdi-clock-outline"></i></span>
                                    </div>
                                </div>
                                <div id="details-errors1"></div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark waves-effect" data-dismiss="modal">Close</button>
                <button id="save" type="button" class="btn btn-primary waves-effect waves-light">Submit</button>
            </div>
        </div>
    </div>
</div><!-- /.modal -->
@endsection

@section('js')
    <script src="{{ URL::asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/clockpicker/bootstrap-clockpicker.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/parsleyjs/js/parsley.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/flatpickr/flatpickr.min.js') }}"></script>

    <script>
        var data_url = "{{ route('listAllSchedule') }}";
        var add_url = "{{ route('addTime') }}";
        var del_url = "{{ route('destroySchedule') }}";
    </script>

    <script src="{{ URL::asset('assets/js/pages/teachers/schedule.js') }}"></script>
@endsection