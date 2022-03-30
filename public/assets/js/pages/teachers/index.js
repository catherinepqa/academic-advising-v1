$(function () {
    var App = {
        baseUrl : window.location.protocol + '//' + window.location.host + '/admin/teachers/',
        csrfToken : $('meta[name="csrf-token"]').attr('content'),

        init: function () {
            this.setElements();
            this.bindEvents();
            App.initDataTable();
        },

        setElements: function () {
            this.$add = $('#saveBtn');
            this.$update = $('#updateBtn');
        },

        bindEvents: function () {
            this.$add.on('click', this.add);
            this.$update.on('click', this.update);
        },

        initDataTable: function() {
            var table = $("#basic-datatable").DataTable({
                responsive: true,
                processing: true,
                serverMethod: 'GET',
                bDestroy: true,
                ajax: data_url,
                columns:[
                    { data: "first_name" },
                    { data: "email" },
                    { data: "employee_number" },
                    { data: "contact_number" },
                    { data: "profile_id", 'className': 'align-center' }
                ],
                columnDefs: [
                    {
                        targets: 0,
                        'render': function (data, type, row){
                            return data+' '+row['last_name'];
                        }
                    },
                    {
                        targets: 4,
                        orderable: false,
                        'render': function (data, type, row){
                            return '<div class="btn-group dropdown action-group">' +
                            '<a href="javascript: void(0);" class="table-action-btn dropdown-toggle arrow-none btn btn-light btn-sm" data-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-cog"></i></a>' +
                            '<div class="dropdown-menu dropdown-menu-right" style="">' +
                                '<a class="dropdown-item" href="javascript: void(0);" onclick="edit('+data+')"><i class="mdi mdi-pencil mr-2 text-muted font-18 vertical-middle"></i>Edit</a>' +
                                '<a class="dropdown-item" href="javascript: void(0);" onclick="deleteData('+data+', '+row['user_id']+')"><i class="mdi mdi-delete mr-2 text-muted font-18 vertical-middle"></i>Delete</a>' +
                            '</div>'+
                        '</div>';
                        }
                    },
                ],
                language:{
                    paginate:{
                        previous:"<i class='mdi mdi-chevron-left'>",
                        next:"<i class='mdi mdi-chevron-right'>"
                    }
                },
                drawCallback:function(){
                    $(".dataTables_paginate > .pagination").addClass("pagination-rounded")
                }
            });

        },

        add : function() {
            if (App.validate()) return;

            Swal.fire({
                title:"Are you sure",
                text: "You want to save this record?",
                type: "warning",
                showCancelButton:!0,
                confirmButtonColor:"#f2993e",
                cancelButtonColor:"#323a46",
                confirmButtonText:"Yes, save it!"
            }).then(function(t){
                if (t.value == true) {
                    $('#add-modal').modal('toggle');
                    $.ajax({
                        url: add_url,
                        type: 'POST',
                        data: $("#addForm").serialize(),
                        dataType: 'json',
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        success: function(data) {
                            if (data.msg == 'success') {
                                App.initDataTable();
                                swal({
                                    title: "Success!",
                                    text: "Successfully created new Teacher Account.",
                                    type: "success",
                                    confirmButtonColor:"#f2993e",
                                }, function () {

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
        },

        update : function() {
            if ($('#updateForm').parsley().validate()) {
                Swal.fire({
                    title:"Are you sure",
                    text: "You want to update this record?",
                    type: "warning",
                    showCancelButton:!0,
                    confirmButtonColor:"#f2993e",
                    cancelButtonColor:"#323a46",
                    confirmButtonText:"Yes, update it!"
                }).then(function(t){
                    if (t.value == true) {
                        $('#update-modal').modal('toggle');
                        $.ajax({
                            url: update_url,
                            type: 'PUT',
                            data: $("#updateForm").serialize(),
                            dataType: 'json',
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            success: function(data) {
                                if (data.message == 'success') {
                                    App.initDataTable();
                                    swal({
                                        title: "Success!",
                                        text: "Successfully updated a teacher account.",
                                        type: "success",
                                        confirmButtonColor:"#f2993e",
                                    }, function () {

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
        },

        validate : function() {
            if (!$('#addForm').parsley().validate()) {
                return true;
            } else {
                return false;
            }
        },
    };

    App.init();
});

$('.addBtn').click(function(){
    $('#add-modal').modal({
        backdrop: 'static',
        keyboard: false
    }, 'show');
});

function edit(id) {
    $('#update-modal').modal({
        backdrop: 'static',
        keyboard: false
    }, 'show');
    
    $.ajax({
        url: edit_url,
        type: 'GET',
        data: {'id' : id},
        dataType: 'json',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        success: function(data) {
            $('.update_first').val(data.first_name);
            $('.update_last').val(data.last_name);
            $('.update_email').val(data.email);
            $('.update_employee_number').val(data.employee_number);
            $('.update_contact').val(data.contact_number);
            $('.profile_id').val(data.profile_id);
            $('.user_id').val(data.user_id);
        },
        error : function(request, status, error) {
            swal("Oops!", "Seems like there is an error. Please try again", "error");
        }
    });
}

function deleteData(id, user_id) {    
    Swal.fire({
        title:"Are you sure",
        text: "You want to delete this record?",
        type: "warning",
        showCancelButton:!0,
        confirmButtonColor:"#f2993e",
        cancelButtonColor:"#323a46",
        confirmButtonText:"Yes, delete it!"
    }).then(function(t){
        if (t.value == true) {
            $.ajax({
                url: del_url,
                type: 'DELETE',
                data: {'profile_id' : id, 'user_id' : user_id},
                dataType: 'json',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function(data) {
                    if (data.message == 'success') {
                        var table = $('#basic-datatable').DataTable();
                        table.ajax.reload();
                        swal({
                            title: "Success!",
                            text: "Successfully deleted a teacher account.",
                            type: "success",
                            confirmButtonColor:"#f2993e",
                        }, function () {
                            
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

//Prevent user from using enter to submit the form
$(document).ready(function() {
    $(window).keydown(function(event){
        if(event.keyCode == 13) {
            event.preventDefault();
            return false;
        }
    });
});