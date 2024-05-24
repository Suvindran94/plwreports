<link rel="stylesheet" href="https://cdn.datatables.net/2.0.0/css/dataTables.dataTables.css" />
@include('Navigation.app')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.standalone.min.css">

    <style>
        html,
        body {
            margin: 0;
            padding: 0;
            overflow: scroll;
            /* overflow-x: hidden; */
        }

        h1 {
            font-family: helvetica, arial, sans-serif;
            width: 100%;
            font-size: 40px;
            font-weight: bold;
            color: white;
            text-align: center;
            left: 0;
            right: 0;
            margin: 0;
            background: url("https://media.giphy.com/media/3ov9jJikNnrKktK1Wg/giphy.gif");
            background-size: cover;
            background-repeat: no-repeat;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .bold-row {
            font-weight: bold;
        }

      
        table.dataTable thead {
            background-color: #d3d3d3;
        }

        table.dataTable th {
            font-size: 14px !important;
        }

        table.dataTable thead th,
        table.dataTable thead td {
            padding: 1px 1px !important;
        }

        table.dataTable tbody th,
        table.dataTable tbody td {
            padding: 0px 0px !important;
        }

        table.dataTable td {
            font-size: 16px !important;
        }

        .table-striped tbody tr.highlight {
            background-color: #ffffff !important;
        }

        .text-right {
            text-align: right !important;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background: #ffffff;
        }

        .table-striped tbody tr:nth-of-type(even) {
            background: #ffffff ;
        }

        .header-group-1 {
            background-color: #d3d3d3 !important;
        }

        .header-group-2 {
            background-color: #9bc2e6 !important;
        }

        .header-group-3 {
            background-color: #ffd966 !important;
        }

        .header-group-4 {
            background-color: #a9d08e !important;
        }

        .tbody-group-1 {
            background-color: #ddebf7 !important;
        }

        .tbody-group-2 {
            background-color: #fff2cc !important;
        }

        .tbody-group-3 {
            background-color: #e2efda !important;
        }

        .weekend-row {
            background-color: #e7e6e6 !important;
        }
			.table-striped > tbody > tr:nth-of-type(odd) > * {
  --bs-table-accent-bg: none;
  color: none;
}
    </style>

		           <!-- Content wrapper -->
           <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
				<h4 class="fw-bold py-3 mb-4">DAILY EFFICIENCY TRACKER</h4>

        <div style="padding-left: 20px; padding-right: 20px; margin-top: -10px;">
            <div class="row" style="height: 35px;">
                <div class="col-6" style="height: 35px;">
				
                </div>
                <div class="col-3" style="height: 35px;">
					
                </div>
                <div class="col-3 custom-position" style="text-align:right; height: 35px;">
                    <div class="input-group" style="margin-bottom: 0px !important;">
                        <h2 class="h4" style="line-height: 1.65; margin-bottom: 0;">Month:</h2>
                       
                            <input id="datepicker" class="form-control custom-width" style="height: 35px; background-color: white;" readonly>
                      
                    </div>
                </div>
            </div>
            <div style="margin-top: 20px;">
                <table id="datatable" class="table table-striped table-bordered" style="width:100%;">
                    <thead>
                        <tr>
                            <th class="header-group-1" style="text-align: center !important; width: 3%;">DAY</th>
                            <th class="header-group-1" style="text-align: center !important; width: 4%;">DATE</th>
                            <th class="header-group-1" style="text-align: center !important; width: 3%;">TOTAL WORKER</th>
                            <th class="header-group-1" style="text-align: center !important; width: 4%;">TOTAL OT HOUR</th>
                            <th class="header-group-2" style="text-align: center !important; width: 4%;">DAILY TARGET</th>
                            <th class="header-group-2" style="text-align: center !important; width: 3%;">PRODUCTION - OPERATOR SCAN (PACK)</th>
                            <th class="header-group-2" style="text-align: center !important; width: 3%;">CUMULATIVE PRODUCTION TOTAL (PACK)</th>
                            <th class="header-group-2" style="text-align: center !important; width: 3%;">ARL</th>
                            <th class="header-group-2" style="text-align: center !important; width: 3%;">WORKING DAY MANPOWER RATIO</th>
                            <th class="header-group-3" style="text-align: center !important; width: 3%;">WAREHOUSE - SCAN RECEIVED (PACK)</th>
                            <th class="header-group-3" style="text-align: center !important; width: 3%;">CUMULATIVE WAREHOUSE RECEIVED (PACK)</th>
                            <th class="header-group-3" style="text-align: center !important; width: 4%;">WH ARL</th>
                            <th class="header-group-4" style="text-align: center !important; width: 3%;">DO (PACK)</th>
                            <th class="header-group-4" style="text-align: center !important; width: 3%;">CUMULATIVE PACK DO (PACK)</th>
                            <th class="header-group-4" style="text-align: center !important; width: 4%;">DO ARL</th>
                        </tr>
                    </thead>
                    <tbody class="tbody-color">
                    </tbody>
                </table>
            </div>

        </div>
    </div>
     @include('Navigation.footer')

            <script src="https://cdn.datatables.net/2.0.0/js/dataTables.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

    <script>
        function formatDate(date) {
            var year = date.getFullYear();
            var month = (date.getMonth() + 1).toString().padStart(2, '0');
            var day = date.getDate().toString().padStart(2, '0');
            return year + '-' + month + '-' + day;
        }

        function formatNumberWithCommas(number) {
            return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }

        $(document).ready(function() {
            function widthResizer(){
                var width = window.innerWidth
                console.log(width)
            }

            widthResizer();

            $('#datepicker').datepicker({
                format: "MM yyyy",
                startView: "months",
                minViewMode: "months",
                orientation: "bottom",
                autoclose: true,
            }).on('changeDate', function(e) {
                // $('#datatable').DataTable().destroy();
                var selectedDate = e.date;
                var firstDayOfMonth = new Date(selectedDate.getFullYear(), selectedDate.getMonth(), 1);
                var formattedFirstDay = formatDate(firstDayOfMonth);

                var lastDateOfMonth = new Date(selectedDate.getFullYear(), selectedDate.getMonth() + 1, 0);
                var formattedLastDay = formatDate(lastDateOfMonth);

						$('#datatable colgroup').empty();
				
                $('#datatable').DataTable({
                    destroy: true,
                    ajax: {
                        type: 'GET',
                        url: '/dailyEffTrackDashAjax/',
                        data: {
                            start_date: formattedFirstDay,
                            end_date: formattedLastDay,
                        },
                    },
                    columns: [{
                            data: 'DAY',
                            className: 'text-left'
                        },
                        {
                            data: 'DATE',
                            className: 'text-left'
                        },
                        {
                            data: 'TOTAL WORKER',
                            className: 'text-right'
                        },
                        {
                            data: 'TOTAL OT HOUR',
                            className: 'text-right'
                        },
                        {
                            data: 'DAILY TARGET',
                            className: 'text-right',
                            render: function(data, type, row) {
                                return formatNumberWithCommas(data);
                            }
                        },
                        {
                            data: 'PRODUCTION - OPERATOR SCAN (PACK)',
                            className: 'text-right',
                            render: function(data, type, row) {
                                return formatNumberWithCommas(data);
                            }
                        },
                        {
                            data: 'CUMULATIVE PRODUCTION TOTAL (PACK)',
                            className: 'text-right',
                            render: function(data, type, row) {
                                return formatNumberWithCommas(data);
                            }
                        },
                        {
                            data: 'ARL',
                            className: 'text-right',
                            render: function(data, type, row) {
                                return formatNumberWithCommas(data);
                            }
                        },
                        {
                            data: 'WORKING DAY MANPOWER RATIO',
                            className: 'text-right'
                        },
                        {
                            data: 'WAREHOUSE - SCAN RECEIVED (PACK)',
                            className: 'text-right',
                            render: function(data, type, row) {
                                return formatNumberWithCommas(data);
                            }
                        },
                        {
                            data: 'CUMULATIVE WAREHOUSE RECEIVED (PACK)',
                            className: 'text-right',
                            render: function(data, type, row) {
                                return formatNumberWithCommas(data);
                            }
                        },
                        {
                            data: 'WH ARL',
                            className: 'text-right',
                            render: function(data, type, row) {
                                return formatNumberWithCommas(data);
                            }
                        },
                        {
                            data: 'DO (PACK)',
                            className: 'text-right',
                            render: function(data, type, row) {
                                return formatNumberWithCommas(data);
                            }
                        },
                        {
                            data: 'CUMULATIVE PACK DO (PACK)',
                            className: 'text-right',
                            render: function(data, type, row) {
                                return formatNumberWithCommas(data);
                            }
                        },
                        {
                            data: 'DO ARL',
                            className: 'text-right',
                            render: function(data, type, row) {
                                return formatNumberWithCommas(data);
                            }
                        }
                    ],
                    paging: false,
                    info: false,
                    searching: false,
                    lengthChange: false,
                    order: [],
                    columnDefs: [{
                        orderable: false,
                        targets: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14]
                    }],
                    createdRow: function(row, data, dataIndex) {
                        if (dataIndex === -1) return;

                        var today = formatDate(new Date());
                        if (data['DATE'] === today) {
                            $(row).find('td').each(function() {
                                $(this).css('font-weight', 'bold');
                            });
                        }

                        if (data['DAY'] === 'Saturday' || data['DAY'] === 'Sunday') {
                            $(row).addClass('weekend-row');
                        } else {
                            $(row).find('td:nth-child(5), td:nth-child(6), td:nth-child(7), td:nth-child(8), td:nth-child(9)').addClass('tbody-group-1');
                            $(row).find('td:nth-child(10), td:nth-child(11), td:nth-child(12)').addClass('tbody-group-2');
                            $(row).find('td:nth-child(13), td:nth-child(14), td:nth-child(15)').addClass('tbody-group-3');
                        }
                    }
                });
            });

            // function initializeDataTable() {
            //     $('#datatable').DataTable({
            //         ajax: {
            //             type: 'GET',
            //             url: '/dailyEffTrackDashAjax/',
            //         },
            //         columns: [{
            //                 data: 'DAY',
            //                 className: 'text-left'
            //             },
            //             {
            //                 data: 'DATE',
            //                 className: 'text-left'
            //             },
            //             {
            //                 data: 'TOTAL WORKER',
            //                 className: 'text-right'
            //             },
            //             {
            //                 data: 'TOTAL OT HOUR',
            //                 className: 'text-right'
            //             },
            //             {
            //                 data: 'DAILY TARGET',
            //                 className: 'text-right'
            //             },
            //             {
            //                 data: 'PRODUCTION - OPERATOR SCAN (PACK)',
            //                 className: 'text-right'
            //             },
            //             {
            //                 data: 'CUMULATIVE PRODUCTION TOTAL (PACK)',
            //                 className: 'text-right'
            //             },
            //             {
            //                 data: 'ARL',
            //                 className: 'text-right'
            //             },
            //             {
            //                 data: 'WORKING DAY MANPOWER RATIO',
            //                 className: 'text-right'
            //             },
            //             {
            //                 data: 'WAREHOUSE - SCAN RECEIVED (PACK)',
            //                 className: 'text-right'
            //             },
            //             {
            //                 data: 'CUMULATIVE WAREHOUSE RECEIVED (PACK)',
            //                 className: 'text-right'
            //             },
            //             {
            //                 data: 'WH ARL',
            //                 className: 'text-right'
            //             },
            //             {
            //                 data: 'DO (PACK)',
            //                 className: 'text-right'
            //             },
            //             {
            //                 data: 'CUMULATIVE PACK DO (PACK)',
            //                 className: 'text-right'
            //             },
            //             {
            //                 data: 'DO ARL',
            //                 className: 'text-right'
            //             }
            //         ],
            //         "paging": false,
            //         "info": false,
            //         "searching": false,
            //         "lengthChange": false,
            //         "order": [],
            //         "columnDefs": [{
            //             "orderable": false,
            //             "targets": [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14]
            //         }],
            //         "createdRow": function(row, data, dataIndex) {
            //             if (dataIndex === -1) return;

            //             if (data['DAY'] === 'Saturday' || data['DAY'] === 'Sunday') {
            //                 $(row).addClass('weekend-row');
            //             } else {
            //                 $(row).find('td:nth-child(5), td:nth-child(6), td:nth-child(7), td:nth-child(8), td:nth-child(9)').addClass('tbody-group-1');
            //                 $(row).find('td:nth-child(10), td:nth-child(11), td:nth-child(12)').addClass('tbody-group-2');
            //                 $(row).find('td:nth-child(13), td:nth-child(14), td:nth-child(15)').addClass('tbody-group-3');
            //             }
            //         }
            //     });
            // }

            // var table = initializeDataTable();
            $("#datepicker").datepicker("setDate", new Date());

            // setInterval(function() {
            //     table.destroy();
            //     table = initializeDataTable();
            // }, 10000);
        });
    </script>
</body>

</html>
