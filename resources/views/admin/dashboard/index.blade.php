@extends('admin.adminTemplate')

@section('title', 'Admin | Dashboard')

@section('css')
    <link href="{{ URL::asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('assets/libs/flatpickr/flatpickr.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .btn {
            width: 100%;
        }

        #searchBtn {
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
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title">Dashboard</h4>
        </div>
    </div>
@endsection

@section('content-1')
<div class="row">
    <div class="col-sm-6 col-xl-4">
        <div class="p-2 text-center card-box">
            <i class="mdi mdi-account-supervisor text-primary mdi-24px"></i>
            <h3><span data-plugin="counterup">{{ $teachers }}</span></h3>
            <p class="text-muted font-15 mb-0">Total Number of Teachers</p>
        </div>
    </div>

    <div class="col-sm-6 col-xl-4">
        <div class="p-2 text-center card-box">
            <i class="mdi mdi-account-group text-primary mdi-24px"></i>
            <h3><span data-plugin="counterup">{{ $students }}</span></h3>
            <p class="text-muted font-15 mb-0">Total Number of Students</p>
        </div>
    </div>

    <div class="col-sm-6 col-xl-4">
        <div class="p-2 text-center card-box">
            <i class="mdi mdi-calendar-month text-primary mdi-24px"></i>
            <h3><span data-plugin="counterup">{{ $events }}</span></h3>
            <p class="text-muted font-15 mb-0">Total Number of Appointments</p>
        </div>
    </div>

    <div class="col-lg-12">
        <div class="card-box pb-2">
            <h4 class="header-title mb-3">Appointment List</h4>
            <div class="row">
                <div class="col-lg-5">
                    <div class="form-group">
                        <label>Start Date</label>
                        <input type="text" id="startDate" class="form-control" placeholder="Date and Time" value="{{ $monday }}">
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="form-group">
                        <label>End Date</label>
                        <input type="text" id="endDate" class="form-control" placeholder="Date and Time" value="{{ $friday }}">
                    </div>
                </div>
                <div class="col-lg-2">
                    <button id="searchBtn" type="button" class="btn btn-primary waves-effect waves-light">Search</button>
                </div>  
            </div>

            <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                <thead>
                    <tr>
                        <th>Student Name</th>
                        <th>Teacher Address</th>
                        <th>Date of Consultation</th>
                        <th>Status</th>
                        <th width="5px"><i class="fe-corner-right-down"></i></th>
                    </tr>
                </thead>
            
                <tbody></tbody>
            </table>
        </div> <!-- end card-box -->
    </div> 
</div>

<div id="details-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body p-4">
                <div class="text-center">
                    <p class="mt-3 event_details"></p>
                    <p class="mt-3">Zoom Link : <a href="#" target="_blank" class="zoom_link"><label class="zoom"></label></a></p>
                </div>
            </div>
            <p class="closedBtn"></p>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@endsection

@section('js')
    <script src="{{ URL::asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/flatpickr/flatpickr.min.js') }}"></script>

    <script>
        var data_url = "{{ route('listAllEvents') }}";
    </script>

    <script src="{{ URL::asset('assets/js/pages/dashboard/index.js') }}"></script>
@endsection