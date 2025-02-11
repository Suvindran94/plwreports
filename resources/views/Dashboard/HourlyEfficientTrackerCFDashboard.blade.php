<link rel="stylesheet" href="https://cdn.datatables.net/2.0.0/css/dataTables.dataTables.css" />
<link href="https://cdn.datatables.net/rowgroup/1.5.0/css/rowGroup.dataTables.min.css" rel="stylesheet">
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
        position: sticky!important;
        top: 120;
        background: #f5f5f9;
        z-index: 11;
    }

    table.dataTable thead {
        position: sticky!important;
        top: 150;
        z-index: 5;
    }

    .btn-close {
        margin: 0;
        padding: 0.5rem;
        transform: none !important;
        box-shadow: none !important;
    }

    #hourlyTotalTable.dataTable thead {
        position: sticky !important;
        top: 0 !important;
        z-index: 5;
    }

    .no-border td {
        border-top: 1px solid rgb(255 255 255 / 30%) !important;
    }

    #hourlyTotalTable th:nth-child(2),
    #hourlyTotalTable td:nth-child(2) {
        width: 50%;
    }

    .hourly-total {
        cursor: pointer;
    }

    .hourly-total.no-pointer {
        cursor: auto;
    }

    #hourlyTotalTable.dataTable th {
        text-align: left !important;
    }

    #hourlyTotalTable.dataTable tbody td:nth-child(1),
    #hourlyTotalTable.dataTable tbody td:nth-child(2) {
        text-align: left !important;
    }

    #hourlyTotalTable.dataTable tbody td:nth-child(3) {
        text-align: center !important;
    }
</style>

<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4 sticky-header">HOURLY EFFICIENCY MONITOR (COMPRESSION FITTINGS) ON {{ Carbon\Carbon::now()->format('d/m/Y') }}
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
        <!-- Hourly Total Modal -->
        <div id="hourlyTotalModal" class="modal fade" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitle">Hourly Total Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <table id="hourlyTotalTable" class="table table-striped table-bordered" style="width:100%;">
                            <thead>
                                <tr>
                                    <th>SO#</th>
                                    <th>STOCKCODE</th>
                                    <th>HOURLY TOTAL (ACTUAL)</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('Navigation.footer')
<script src="https://cdn.datatables.net/2.0.0/js/dataTables.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdn.datatables.net/rowgroup/1.5.0/js/dataTables.rowGroup.min.js"></script>

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

            $('#date-heading').text(`HOURLY EFFICIENCY MONITOR (COMPRESSION FITTINGS) ON ${formattedDate} (${formattedDay})`);

            $('#datatable').DataTable({
                // scrollCollapse: true,
                // scrollY: 600,
                destroy: true,
                ajax: {
                    type: 'GET',
                    url: '/hourlyEffTrackCFDashAjax/',
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
                        className: 'text-right'
                    },
                    {
                        data: 'DAILY TARGET (PACK)',
                        className: 'text-right'
                    },
                    {
                        data: 'HOURLY TOTAL (ACTUAL)',
                        className: 'text-right',
                        render: function(data, type, row) {
                            if (data === 0 || data === '0') {
                                return `<span class="hourly-total no-pointer" data-id="${row.id}" data-value="${data}">${data}</span>`;
                            }
                            return `<span class="hourly-total" data-id="${row.id}" data-value="${data}">${data}</span>`;
                        }
                    },
                    {
                        data: 'TODAY\'S TOTAL (PACK)',
                        className: 'text-right'
                    },
                    {
                        data: 'ACHIEVEMENT (PACK)',
                        className: 'text-right'
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
                    var achievement = data['ACHIEVEMENT (PACK)'];
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

        $(document).on('click', '.hourly-total', function() {
            var dataValue = $(this).data('value');

            if (dataValue === 0 || dataValue === '0') {
                return;
            }

            var row = $(this).closest('tr');
            var selectedTime = row.find('td').eq(0).text().trim();
            var formattedFirstDay = formatDate($('#datepicker').datepicker('getDate'));
            var formattedLastDay = formatDate($('#datepicker').datepicker('getDate'));

            $('#hourlyTotalTable colgroup').remove();

            if ($.fn.DataTable.isDataTable('#hourlyTotalTable')) {
                $('#hourlyTotalTable').DataTable().destroy();
            }

            $('#hourlyTotalTable tbody').empty();

            // let seenSO = new Set();
            // let previousSO = '';

            $('#hourlyTotalTable').DataTable({
                ajax: {
                    type: 'GET',
                    url: '/gethourlyEfficientCEDetails',
                    data: {
                        time: selectedTime,
                        start_date: formattedFirstDay,
                        end_date: formattedLastDay,
                    },
                    dataSrc: 'data'
                },
                columns: [
                    {
                        data: 'SO#'
                    },
                    {
                        data: 'STOCKCODE'
                    },
                    {
                        data: 'HOURLY TOTAL (ACTUAL)'
                    }
                ],
                // createdRow: function(row, data) {
                //     if (seenSO.has(data['SO#'])) {
                //         $('td:first', row).empty();
                //         $(row).addClass('no-border');
                //     } else {
                //         seenSO.add(data['SO#']);
                //     }
                //     previousSO = data['SO#'];
                // },
                paging: false,
                info: false,
                searching: false,
                lengthChange: false,
                order: [],
                columnDefs: [{
                    orderable: false,
                    targets: '_all'
                }]
            });

            $('#hourlyTotalModal').modal('show');
        });

        $('#hourlyTotalModal').on('hidden.bs.modal', function () {
            if ($.fn.DataTable.isDataTable('#hourlyTotalTable')) {
                $('#hourlyTotalTable').DataTable().destroy();
            }
            $('#hourlyTotalTable tbody').empty();
            $('#hourlyTotalTable colgroup').remove();
        });

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
            var headerText = 'HOURLY EFFICIENCY MONITOR (COMPRESSION FITTINGS) ON ' + formattedDate + ' (' + dayOfWeek + ')';
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
