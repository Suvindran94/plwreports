<link rel="stylesheet" href="https://cdn.datatables.net/2.0.0/css/dataTables.dataTables.css" />
@include('Navigation.app')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.standalone.min.css">

    <style>
        html,
        body {
            margin: 0;
            padding: 0;
            overflow: scroll;
        }

        .datepicker {
            opacity: 1 !important;
        
        }

        h1 {
            font-family: helvetica, arial, sans-serif;
            width: 100%;
            font-size: 40px;
            font-weight: bold;
            color: white;
            text-align: left;
            left: 0;
            right: 0;
            margin: 0;
            background: url("https://media.giphy.com/media/3ov9jJikNnrKktK1Wg/giphy.gif");
            background-size: cover;
            background-repeat: no-repeat;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

      

      

        table.dataTable thead {
            background-color: #d3d3d3;
        }

        table.dataTable th {
            font-size: 18px;
        }

        table.dataTable tbody th,
        table.dataTable tbody td {
            padding: 1px 1px;
        }

        table.dataTable td {
            font-size: 18px;
        }

        .table-striped tbody tr.highlight {
            background-color: #a1b7e5 !important;
        }

		 .table-striped tbody tr.highlight {
            background-color: #a1b7e5 !important;
        }
		
        .text-right {
            text-align: right;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background: white;
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
				<h4 class="fw-bold py-3 mb-4">HOURLY EFFICIENCY MONITOR ON {{ Carbon\Carbon::now()->format('d/m/Y') }}
                        ({{ Carbon\Carbon::now()->format('l') }})</h4>

        <div style="padding-left: 20px; padding-right: 20px;">
            <div class="row" style="height: 35px;">
                <div class="col-6" style="height: 35px;">
                </div>
                <div class="col-3" style="height: 35px;">
                </div>
                <div class="col-3" style="text-align:right; height: 35px;">
                    <div class="input-group" style="margin-bottom: 0px !important;">
                        <h2 class="h4" style="line-height: 1.65; margin-bottom: 0;">Date:</h2>
                        <div class="input-group" style="width: 80%; height: 35px;">
                            <input id="datepicker" class="form-control custom-width" style="height: 35px; background-color: white;" readonly>
                        </div>
                    </div>
                </div>
            </div>
         
                <table id="datatable" class="table table-striped table-bordered" style="width:100%;">
                    <thead>
                        <tr>
                            <th style="text-align: center;">TIME</th>
                            <th style="text-align: center;">HOURLY TARGET (TARGET)</th>
                            <th style="text-align: center;">DAILY TARGET (PACK)</th>
                            <th style="text-align: center;">HOURLY TOTAL (ACTUAL)</th>
                            <th style="text-align: center;">TODAY'S TOTAL (PACK)</th>
                            <th style="text-align: center;">ACHIEVEMENT (PACK)</th>
                        </tr>
                    </thead>
                    <tbody>
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

        function formatDate2(date) {
            var day = ("0" + date.getDate()).slice(-2);
            var month = ("0" + (date.getMonth() + 1)).slice(-2);
            var year = date.getFullYear();
            return day + "/" + month + "/" + year;
        }

        function formatDay(date) {
            var days = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
            return days[date.getDay()];
        }
		
		            function getCurrentHour() {
                var currentdate = new Date();
                var currentHour = currentdate.getHours();
                var formattedHour = currentHour.toString().padStart(2, '0') + ':00:00';
                return formattedHour;
            }


        // This is without auto refresh
        // $(document).ready(function() {
        //     $('#datepicker').datepicker({
        //         format: "dd/mm/yyyy",
        //         orientation: "bottom",
        //         autoclose: true,
        //         todayBtn: "linked",
        //     }).on('changeDate', function(e) {
        //         var selectedDate = e.date;
        //         var formattedDate = formatDate2(selectedDate);
        //         var formattedDay = formatDay(selectedDate);

        //         $('#date-heading').text(`HOURLY EFFICIENCY MONITOR ON ${formattedDate} (${formattedDay})`);

        //         var formattedFirstDay = formatDate(selectedDate);
        //         var formattedLastDay = formatDate(selectedDate);

        //         $('#datatable').DataTable({
        //             destroy: true,
        //             ajax: {
        //                 type: 'GET',
        //                 url: '/productionDailyReportDashAjax/',
        //                 data: {
        //                     start_date: formattedFirstDay,
        //                     end_date: formattedLastDay,
        //                 },
        //             },
        //             columns: [{
        //                     data: 'TIME',
        //                     className: 'text-right'
        //                 },
        //                 {
        //                     data: 'HOURLY TARGET (TARGET)',
        //                     className: 'text-right'
        //                 },
        //                 {
        //                     data: 'DAILY TARGET (PACK)',
        //                     className: 'text-right'
        //                 },
        //                 {
        //                     data: 'HOURLY TOTAL (ACTUAL)',
        //                     className: 'text-right'
        //                 },
        //                 {
        //                     data: 'TODAY\'S TOTAL (PACK)',
        //                     className: 'text-right'
        //                 },
        //                 {
        //                     data: 'ACHIEVEMENT (PACK)',
        //                     className: 'text-right'
        //                 }
        //             ],
        //             "paging": false,
        //             "info": false,
        //             "searching": false,
        //             "lengthChange": false,
        //             "order": [],
        //             "columnDefs": [{
        //                 "orderable": false,
        //                 "targets": [0, 1, 2, 3, 4, 5]
        //             }],
        //             "rowCallback": function(row, data) {
        //                 var currentHour = data['TIME'];
        //                 var highlightClass = currentHour === "{{ $currentHour }}" ? 'highlight' : '';
        //                 if (highlightClass) {
        //                     $(row).addClass(highlightClass);
        //                 }
        //             },
        //             createdRow: function(row, data) {
        //                 var currentHour = data['TIME'];
        //                 var achievement = data['ACHIEVEMENT (PACK)'];

        //                 if (currentHour === "{{ $currentHour }}") {
        //                     if (parseFloat(achievement) < 0) {
        //                         $('td', row).eq(5).css({
        //                             'background-color': 'red',
        //                             'color': 'white',
        //                             'font-weight': '800'
        //                         });
        //                     } else {
        //                         $('td', row).eq(5).css({
        //                             'color': 'red',
        //                         });
        //                     }
        //                 }
        //             }
        //         });
        //     });

        //     $("#datepicker").datepicker("setDate", new Date());
        // });

        $(document).ready(function() {
            function initializeDataTable(date) {
                var formattedDate = formatDate2(date);
                var formattedDay = formatDay(date);
                var formattedFirstDay = formatDate(date);
                var formattedLastDay = formatDate(date);
				
				var currhr = "{{ $currentHour }}";
				
				console.log(currhr);
				
		
				
				$('#datatable colgroup').empty();

                $('#date-heading').text(`HOURLY EFFICIENCY MONITOR ON ${formattedDate} (${formattedDay})`);

                $('#datatable').DataTable({
                    destroy: true,
                    ajax: {
                        type: 'GET',
                        url: '/productionDailyReportDashAjax/',
                        data: {
                            start_date: formattedFirstDay,
                            end_date: formattedLastDay,
                        },
                    },
                    columns: [
                        { data: 'TIME', className: 'text-right' },
                        { data: 'HOURLY TARGET (TARGET)', className: 'text-right' },
                        { data: 'DAILY TARGET (PACK)', className: 'text-right' },
                        { data: 'HOURLY TOTAL (ACTUAL)', className: 'text-right' },
                        { data: 'TODAY\'S TOTAL (PACK)', className: 'text-right' },
                        { data: 'ACHIEVEMENT (PACK)', className: 'text-right' }
                    ],
                    paging: false,
                    info: false,
                    searching: false,
                    lengthChange: false,
                    order: [],
                    columnDefs: [
                        { orderable: false, targets: [0, 1, 2, 3, 4, 5] }
                    ],
					
                    rowCallback: function(row, data) {
                        var currentHour = data['TIME'];
					var selecteddateString = $('#datepicker').val(); // Assuming this is in the format "24/05/2024"
var selecteddate = new Date(selecteddateString);

var todaysdate = new Date();
var todaysdateFormatted = ('0' + todaysdate.getDate()).slice(-2) + '/' + ('0' + (todaysdate.getMonth() + 1)).slice(-2) + '/' + todaysdate.getFullYear();
console.log(selecteddateString, todaysdateFormatted);

var highlightClass = currentHour === "{{ $currentHour }}" && selecteddateString === todaysdateFormatted ? 'highlight' : '';

                        if (highlightClass) {
                            $(row).addClass(highlightClass);
                        }
                    },
                    createdRow: function(row, data) {
                        var currentHour = data['TIME'];
                        var achievement = data['ACHIEVEMENT (PACK)'];
						var selecteddateString = $('#datepicker').val(); // Assuming this is in the format "24/05/2024"
var selecteddate = new Date(selecteddateString);

var todaysdate = new Date();
var todaysdateFormatted = ('0' + todaysdate.getDate()).slice(-2) + '/' + ('0' + (todaysdate.getMonth() + 1)).slice(-2) + '/' + todaysdate.getFullYear();
console.log(selecteddateString, todaysdateFormatted);

                        if (currentHour === "{{ $currentHour }}" && selecteddateString === todaysdateFormatted) {
                            if (parseFloat(achievement) < 0) {
                                $('td', row).eq(5).css({
                                    'background-color': 'red',
                                    'color': 'white',
                                    'font-weight': '800'
                                });
                            } else {
                                $('td', row).eq(5).css({
                                    'color': 'red',
                                });
                            }
                        }
                    }
                });
            }

            $('#datepicker').datepicker({
                format: "dd/mm/yyyy",
                orientation: "bottom",
                autoclose: true,
                todayBtn: "linked",
            }).on('changeDate', function(e) {
                var selectedDate = e.date;
                initializeDataTable(selectedDate);
            });

            var initialDate = new Date();
			
            $("#datepicker").datepicker("setDate", initialDate);

	
            initializeDataTable(initialDate);

          
        });
    </script>
</body>

</html>
