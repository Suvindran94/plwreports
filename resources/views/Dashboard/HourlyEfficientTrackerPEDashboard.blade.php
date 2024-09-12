<link rel="stylesheet" href="https://cdn.datatables.net/2.0.0/css/dataTables.dataTables.css" />
@include('Navigation.app')
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.standalone.min.css">

<style>
    html,
    body {
        margin: 0;
        padding: 0;
    }

    .datepicker {
        opacity: 1 !important;
    }

    h4 {
        font-size: 1.55vw;
    }

    .table-container {
        display: flex;
        flex-direction: column;
        height: 100vh;
        width: 100%;
        overflow: hidden;
        margin-top: 0vh !important;
    }

    .custom-width {
        height: 4vh !important;
        font-size: 1vw !important;
        margin-top: -1vh !important;
    }

    .custom-input {
        width: 70% !important;
    }

    .custom-position {
        margin-top: 0vh !important;
    }

    .h4 {
        font-size: 1.2vw !important;
        line-height: 1 !important;
    }

    table.dataTable thead {
        background-color: #d3d3d3;
    }

    table.dataTable th {
        font-size: 1vw !important;
    }

    table.dataTable tbody th,
    table.dataTable tbody td {
        padding: 1px 1px;
    }

    table.dataTable td {
        font-size: 1.15vw !important;
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

    .table-striped tbody tr:nth-of-type(even) {
        background: #f5f5f9;
    }

    .table-striped>tbody>tr:nth-of-type(odd)>* {
        --bs-table-accent-bg: none;
        color: none;
    }

    .sticky-header {
        position: sticky !important;
        top: 60 !important;
        background: #f5f5f9;
        z-index: 11 !important;
        height: 60px;
    }

    .sticky-date {
        position: sticky !important;
        top: 120;
        background: #f5f5f9;
        z-index: 11;
    }

    table.dataTable thead {
        position: sticky !important;
        top: 150;
        z-index: 5;
    }
</style>

<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4 sticky-header">HOURLY EFFICIENCY MONITOR (PE) ON
            {{ Carbon\Carbon::now()->format('d/m/Y') }}
            ({{ Carbon\Carbon::now()->format('l') }})</h4>

        <div class="sticky-date" style="padding-left: 20px; padding-right: 20px;">
            <div class="row" style="height: 35px;">
                <div class="col-6" style="height: 35px;">
                </div>
                <div class="col-3" style="height: 35px;">
                </div>
                <div class="col-3" style="text-align:right; height: 35px;">
                    <div class="input-group" style="margin-bottom: 0px !important;">
                        <h2 class="h4" style="line-height: 1.65; margin-bottom: 0;">Date:</h2>
                        <div class="input-group" style="width: 80%; height: 35px;">
                            <input id="datepicker" class="form-control custom-width"
                                style="height: 35px; background-color: white;" readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <table id="datatable" class="table table-striped table-bordered" style="width:100%;">
                <thead>
                    <tr>
                        <th style="text-align: center;">TIME</th>
                        <th style="text-align: center;">HOURLY TARGET (TARGET)</th>
                        <th style="text-align: center;">DAILY TARGET (KG)</th>
                        <th style="text-align: center;">HOURLY TOTAL (ACTUAL)</th>
                        <th style="text-align: center;">TODAY'S TOTAL (KG)</th>
                        <th style="text-align: center;">ACHIEVEMENT (KG)</th>
                        <th style="text-align: center;">WASTE (KG)</th>
                        <th style="text-align: center;">TODAY'S WASTE (KG)</th>
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

    $(document).ready(function() {
        function initializeDataTable(date) {
            var formattedDate = formatDate2(date);
            var formattedDay = formatDay(date);
            var formattedFirstDay = formatDate(date);
            var formattedLastDay = formatDate(date);

            var currhr = "{{ $currentHour }}";

            $('#datatable colgroup').empty();

            $('#date-heading').text(`HOURLY EFFICIENCY MONITOR (PE) ON ${formattedDate} (${formattedDay})`);

            $('#datatable').DataTable({
                destroy: true,
                ajax: {
                    type: 'GET',
                    url: '/hourlyEffTrackPEDashAjax/',
                    data: {
                        start_date: formattedFirstDay,
                        end_date: formattedLastDay,
                    },
                },
                columns: [{
                        data: 'TIME',
                        className: 'text-right'
                    },
                    {
                        data: 'HOURLY TARGET (TARGET)',
                        className: 'text-right',
                        render: function(data) {
                            return parseFloat(data).toFixed(3);
                        }
                    },
                    {
                        data: 'DAILY TARGET (KG)',
                        className: 'text-right',
                        render: function(data) {
                            return parseFloat(data).toFixed(3);
                        }
                    },
                    {
                        data: 'HOURLY TOTAL (ACTUAL)',
                        className: 'text-right',
                        render: function(data) {
                            return parseFloat(data).toFixed(3);
                        }
                    },
                    {
                        data: 'TODAY\'S TOTAL (KG)',
                        className: 'text-right',
                        render: function(data) {
                            return parseFloat(data).toFixed(3);
                        }
                    },
                    {
                        data: 'ACHIEVEMENT (KG)',
                        className: 'text-right',
                        render: function(data) {
                            return parseFloat(data).toFixed(3);
                        }
                    },
                    {
                        data: 'WASTE (KG)',
                        className: 'text-right',
                        render: function(data) {
                            return parseFloat(data).toFixed(3);
                        }
                    },
                    {
                        data: 'TODAY\'S WASTE (KG)',
                        className: 'text-right',
                        render: function(data) {
                            return parseFloat(data).toFixed(3);
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
                    targets: [0, 1, 2, 3, 4, 5]
                }],

                rowCallback: function(row, data) {
                    var currentHour = data['TIME'];
                    var selecteddateString = $('#datepicker').val(); // Assuming this is in the format "24/05/2024"
                    var selecteddate = new Date(selecteddateString);

                    var todaysdate = new Date();
                    var todaysdateFormatted = ('0' + todaysdate.getDate()).slice(-2) + '/' + ('0' +
                        (todaysdate.getMonth() + 1)).slice(-2) + '/' + todaysdate.getFullYear();

                    var highlightClass = currentHour === "{{ $currentHour }}" &&
                        selecteddateString === todaysdateFormatted ? 'highlight' : '';

                    if (highlightClass) {
                        $(row).addClass(highlightClass);
                    }
                },
                createdRow: function(row, data) {
                    var currentHour = data['TIME'];
                    var achievement = data['ACHIEVEMENT (KG)'];
                    var selecteddateString = $('#datepicker').val(); // Assuming this is in the format "24/05/2024"
                    var selecteddate = new Date(selecteddateString);

                    var todaysdate = new Date();
                    var todaysdateFormatted = ('0' + todaysdate.getDate()).slice(-2) + '/' + ('0' +
                        (todaysdate.getMonth() + 1)).slice(-2) + '/' + todaysdate.getFullYear();

                    if (currentHour === "{{ $currentHour }}" && selecteddateString ===
                        todaysdateFormatted) {
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
                },
                drawCallback: function(settings) {
                    scrollToHighlightedRow();
                }
            });
        }

        function scrollToHighlightedRow() {
            var highlightedRow = $('.highlight').first();
            if (highlightedRow.length > 0) {
                var container = $('html, body');
                var topOffset = highlightedRow.offset().top - $('.sticky-header').outerHeight(true) - 200;
                container.stop().animate({
                    scrollTop: topOffset
                }, 500);
            }
        }

        $('#datepicker').datepicker({
            format: "dd/mm/yyyy",
            orientation: "bottom",
            autoclose: true,
            todayBtn: "linked",
        }).on('changeDate', function(e) {
            var selectedDate = moment(e.date).format('DD/MM/YYYY');
            updateHeader(selectedDate);

            var selectedDates = e.date;
            initializeDataTable(selectedDates);
        });

        function updateHeader(date) {
            var formattedDate = moment(date, "DD/MM/YYYY").format('DD/MM/YYYY');
            var dayOfWeek = moment(date, "DD/MM/YYYY").format('dddd');
            var headerText = 'HOURLY EFFICIENCY MONITOR (PE) ON ' + formattedDate + ' (' + dayOfWeek + ')';
            $('.fw-bold.py-3.mb-4').text(headerText);
        }

        var initialDate = new Date();

        $("#datepicker").datepicker("setDate", initialDate);

        initializeDataTable(initialDate);
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.30.1/moment.min.js"></script>
</body>

</html>
