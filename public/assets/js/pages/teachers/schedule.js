$(function () {
    var App = {
        //baseUrl : window.location.protocol + '//' + window.location.host + '/admin/teachers/',
        csrfToken : $('meta[name="csrf-token"]').attr('content'),

        init: function () {
            this.setElements();
            this.bindEvents();
            App.initDataTable();
        },

        setElements: function () {
            this.$search = $('#searchBtn');
            this.$save = $('#save');

            $("#startDate").flatpickr({
                dateFormat:"Y-m-d"
            });

            $("#endDate").flatpickr({
                dateFormat:"Y-m-d"
            });

            $(".start_time").clockpicker({
                placement:"bottom",
                align:"left",
                autoclose:!0,
                default:"now"
            });
    
            $(".end_time").clockpicker({
                placement:"bottom",
                align:"left",
                autoclose:!0,
                default:"now"
            });
        },

        bindEvents: function () {
            this.$search.on('click', this.search);
            this.$save.on('click', this.save);
        },

        initDataTable: function() {
            var startDt = $('#startDate').val();
            var endDt = $('#endDate').val();

            var table = $("#basic-datatable").DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                serverMethod: 'GET',
                bLengthChange: false,
                bFilter: false,
                pageLength: 25,
                bInfo: false,
                bDestroy: true,
                ajax: data_url,
                search: {
                    'search' : {
                        'date_from'   : startDt,
                        'date_to'     : endDt
                    }
                },
                columns:[
                    { data: "date" },
                    { data: "day" },
                    { data: "start_date_time" },
                    { data: "duration" }
                ],
                columnDefs: [
                    {
                        targets: 2,
                        orderable: false,
                        'render': function (data, type, row){
                            return timeFormatter(data)+' to '+timeFormatter(row['end_date_time']);
                        }
                    },
                    {
                        targets: 3,
                        orderable: false,
                        'render': function (data, type, row){
                            return data+' minutes';
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

        search : function() {
            App.initDataTable();
        },

        save : function() {
            if ($('#timeForm').parsley().validate()) {
                Swal.fire({
                    title:"Are you sure",
                    text: "You want to save this record?",
                    type: "warning",
                    showCancelButton:!0,
                    confirmButtonColor:"#f2993e",
                    cancelButtonColor:"#323a46",
                    confirmButtonText:"Yes, save it!"
                }).then(function(t){
                    $('#preloader').removeClass('hidden');
                    $('#status').removeClass('hidden');
                    if (t.value == true) {
                        $('#time-modal').modal('toggle');
                        $.ajax({
                            url: add_url,
                            type: 'POST',
                            data: $("#timeForm").serialize(),
                            dataType: 'json',
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            success: function(data) {
                                if (data.msg == 'success') {
                                    $('#preloader').addClass('hidden');
                                    $('#status').addClass('hidden');
                                    swal({
                                        title: "Success!",
                                        text: "You successfully added your time availability.",
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
                }) 
            }
        },
    };

    App.init();
});

$('#date-list').change(function(){
    $('.selected-day').val($(this).val());
    $('.selected-date').val($(this).find(":selected").text());
});

function addTime() {
    $('#time-modal').modal({
        backdrop: 'static',
        keyboard: false
    }, 'show');
}

function timeFormatter(dateTime) {
    var date = new Date(dateTime);
    if (date.getHours()>=12){
        var hour = parseInt(date.getHours()) - 12;
        var amPm = "PM";
    } else {
        var hour = date.getHours(); 
        var amPm = "AM";
    }
    var mins = date.getMinutes() == '0' ? '00' : date.getMinutes();
    var time = hour + ":" + mins + " " + amPm;
    return time;
}

function deleteSched(id) {
    Swal.fire({
            title:"Are you sure",
            text: "You want to delete this schedule?",
            type: "warning",
            showCancelButton:!0,
            confirmButtonColor:"#f2993e",
            cancelButtonColor:"#323a46",
            confirmButtonText:"Yes, delete it!"
        }).then(function(t){
            $('#preloader').removeClass('hidden');
            $('#status').removeClass('hidden');
            if (t.value == true) {
                $.ajax({
                    url: del_url,
                    type: 'DELETE',
                    data: {'schedule_id' : id},
                    dataType: 'json',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    success: function(data) {
                        if (data.message == 'success') {
                            $('#preloader').addClass('hidden');
                            $('#status').addClass('hidden');
                            swal({
                                title: "Success!",
                                text: "You successfully deleted a schedule.",
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
        })
}