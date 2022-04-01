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

            $("#startDate").flatpickr({
                dateFormat:"Y-m-d"
            });

            $("#endDate").flatpickr({
                dateFormat:"Y-m-d"
            });
        },

        bindEvents: function () {
            this.$search.on('click', this.search);
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
                    { data: "student_name" },
                    { data: "teacher_name" },
                    { data: "start_date" },
                    { data: "status" },
                    { data: "title", 'className': 'align-center' }
                ],
                columnDefs: [
                    {
                        targets: 3,
                        'render': function (data, type, row){
                            var status = '';
                            if (data == 'Approved') {
                                status = '<span class="badge badge-soft-success">'+data+'</span>';
                            } else if (data == 'Declined') {
                                status = '<span class="badge badge-soft-danger">'+data+'</span>';
                            } else {
                                status = '<span class="badge badge-soft-warning">'+data+'</span>';
                            }

                            return status;
                        }
                    },
                    {
                        targets: 4,
                        orderable : false,
                        'render': function (data, type, row){
                            return '<div class="btn-group dropdown action-group">' +
                            '<a href="javascript: void(0);" class="table-action-btn dropdown-toggle arrow-none btn btn-light btn-sm" data-toggle="dropdown" aria-expanded="false"><i class="mdi mdi-cog"></i></a>' +
                            '<div class="dropdown-menu dropdown-menu-right" style="">' +
                                '<a class="dropdown-item" href="javascript: void(0);" onclick="view('+row['event_id']+')"><i class="mdi mdi-eye mr-2 text-muted font-18 vertical-middle"></i>View Details</a>' +
                                '<input type="hidden" class="details-'+row['event_id']+'" value="'+data+'">' +
                                '<input type="hidden" class="zoom-'+row['event_id']+'" value="'+row['zoom_link']+'">' +
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

        search : function() {
            App.initDataTable();
        },
    };

    App.init();
});

function view(id) {
    $('#details-modal').modal('show');
    var details = $('.details-'+id).val();
    var zoom_link = $('.zoom-'+id).val();

    $('.event_details').html(details);
    $('.zoom').html(zoom_link);
    $('.zoom_link').attr("href", zoom_link);
}