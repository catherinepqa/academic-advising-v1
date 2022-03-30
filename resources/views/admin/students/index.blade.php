@extends('admin.adminTemplate')

@section('title', 'Admin | Students List')

@section('css')
    <link href="{{ URL::asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ URL::asset('assets/libs/parsleyjs/css/parsley.css') }}">

    <style>
        .required {
            color: #ed556c;
        }
        .align-center {
            text-align: center;
        }
    </style>
@endsection

@section('page-header')
<div class="col-12">
    <div class="page-title-box">
        <div class="page-title-right">
            <button type="button" class="btn btn-primary waves-effect waves-light addBtn">
                <i class="fe-plus"></i>  Add New
            </button>
        </div>
        <h4 class="page-title">Students List</h4>
    </div>
</div>
@endsection

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>Full Name</th>
                            <th>Email Address</th>
                            <th>Student Number</th>
                            <th>Contact Number</th>
                            <th width="5px"><i class="fe-corner-right-down"></i></th>
                        </tr>
                    </thead>
                
                    <tbody></tbody>
                </table>

            </div> <!-- end card body-->
        </div> <!-- end card -->
    </div><!-- end col-->
</div>
<!-- end row-->


<div id="add-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add New Student</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body p-4">
                <form id="addForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="field-1" class="control-label">First Name</label><span class="required"> * </span>
                                <input type="text" class="form-control" id="field-1" name="first_name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="field-2" class="control-label">Last Name</label><span class="required"> * </span>
                                <input type="text" class="form-control" id="field-2" name="last_name" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="field-3" class="control-label">Email Address</label><span class="required"> * </span>
                                <input type="email" class="form-control" id="field-3" name="email" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="field-4" class="control-label">Student Number</label><span class="required"> * </span>
                                <input type="text" class="form-control" id="field-4" name="student_number" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="field-5" class="control-label">Contact Number</label>
                                <input type="text" class="form-control" id="field-5" name="contact_number">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark waves-effect" data-dismiss="modal">Close</button>
                <button id="saveBtn" type="button" class="btn btn-primary waves-effect waves-light">Save changes</button>
            </div>
        </div>
    </div>
</div><!-- /.modal -->

<div id="update-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Update Information</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body p-4">
                <form id="updateForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="field-1" class="control-label">First Name</label><span class="required"> * </span>
                                <input type="text" class="form-control update_first" id="field-1" name="first_name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="field-2" class="control-label">Last Name</label><span class="required"> * </span>
                                <input type="text" class="form-control update_last" id="field-2" name="last_name" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="field-3" class="control-label">Email Address</label><span class="required"> * </span>
                                <input type="email" class="form-control update_email" id="field-3" name="email" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="field-4" class="control-label">Student Number</label><span class="required"> * </span>
                                <input type="text" class="form-control update_student_number" id="field-4" name="student_number">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="field-5" class="control-label">Contact Number</label>
                                <input type="text" class="form-control update_contact" id="field-5" name="contact_number">
                                <input type="hidden" class="profile_id" id="field-5" name="profile_id">
                                <input type="hidden" class="user_id" id="field-5" name="user_id">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark waves-effect" data-dismiss="modal">Close</button>
                <button id="updateBtn" type="button" class="btn btn-primary waves-effect waves-light">Update</button>
            </div>
        </div>
    </div>
</div><!-- /.modal -->


@endsection

@section('js')
    <script src="{{ URL::asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/parsleyjs/js/parsley.min.js') }}"></script>

    <script>
        var add_url     = "{{ route('studentAdd') }}";
        var data_url    = "{{ route('studentDataList') }}";
        var edit_url    = "{{ route('studentDetails') }}";
        var update_url  = "{{ route('studentUpdateData') }}";
        var del_url     = "{{ route('studentDeleteData') }}";
    </script>

    <script src="{{ URL::asset('assets/js/pages/students/index.js') }}"></script>
@endsection