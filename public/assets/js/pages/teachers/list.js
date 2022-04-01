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
                    { data: "title" },
                    { data: "zoom_link" },
                    { data: "status" }
                ],
                columnDefs: [
                    {
                        targets: 2,
                        orderable: false,
                        'render': function (data, type, row){
                            return '<a href="'+data+'" target="_blank">'+data+'</a>';
                        }
                    },
                    {
                        targets: 3,
                        orderable: false,
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
                    }
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