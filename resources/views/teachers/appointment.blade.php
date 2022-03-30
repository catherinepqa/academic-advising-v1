@extends('teachers.teacherTemplate')

@section('title', 'Teacher | Appointment List')

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
            <h4 class="page-title">Appointment List</h4>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card-box">
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
            </div> 
        </div>
    </div> 

<div class="row">
    <div class="col-lg-12">
        <div class="card-box pb-2">
            <table id="basic-datatable" class="table dt-responsive">
                <thead>
                    <tr>
                        <th width="20%">Student Name</th>
                        <th width="40%">Details</th>
                        <th>Zoom Link</th>
                        <th>Status</th>
                    </tr>
                </thead>
            
                <tbody></tbody>
            </table>
        </div> <!-- end card-box -->
    </div> 
</div>
</div>
@endsection

@section('js')
    <script src="{{ URL::asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/flatpickr/flatpickr.min.js') }}"></script>

    <script>
        var data_url = "{{ route('appointmentList') }}";
    </script>

    <script src="{{ URL::asset('assets/js/pages/teachers/list.js') }}"></script>
@endsection