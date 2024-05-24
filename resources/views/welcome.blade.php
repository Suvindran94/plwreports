<link rel="stylesheet" href="https://cdn.datatables.net/2.0.0/css/dataTables.dataTables.css" />
@include('Navigation.app')

  
<style>
    .card{
        padding:20px;
    }

    .dt-input{
        margin-right:5px;
    }

    th {
    text-align: center !important;
}

td {
    text-align: center  !important;
}

tr {
    text-align: center  !important;
}

.progress {
  display: flex;
  height: 20px;
  overflow: hidden;
  font-size: 13px !important;
  font-weight: bold !important;
  background-color: rgba(67, 89, 113, 0.1);
  border-radius: 10rem;
}

</style>

           <!-- Content wrapper -->
           <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
             <h4 class="fw-bold py-3 mb-4">Ordering Dashboard 
    @if(request()->has('load'))
        <a href="/" type="button" class="btn rounded-pill btn-danger" style="float:right;" hidden>Cancel</a>
    @else
        <a href="/?load=1" type="button" class="btn rounded-pill btn-primary" style="float:right;" hidden>Load OutStanding Process Time (hr)</a>
    @endif
</h4>

<div class="card" style="margin-bottom: 30px;">
<div class="table-responsive text-nowrap table-responsive">
                  <table class="table table-bordered"style="width:100%;">
    <tr>
        <th style="width:25%;">Production</th>
        <th style="width:25%; background-color:lightyellow;"><h5 id="prodperc"></h5></th>
        <th style="width:25%;">OutStanding SO</th>
        <th style="width:25%; background-color:lightyellow;"><h5 id="totalso"></h5></th>
    </tr>
    <tr>
        <th>Warehouse</th>
        <th style="background-color:lightyellow;"><h5 id="whperc"></h5></th>
        <th>Outstanding MO</th>
        <th style="background-color:lightyellow;"><h5 id="totalmo"></h5></th>
    </tr>
</table>
</div>
</div>


                          <!-- Basic Bootstrap Table -->
                          <div class="card">
                <div class="table-responsive text-nowrap table-responsive">
                  <table class="table table-bordered" id="myTable" style="width:100%;">
                    <thead>
                    <tr>
        <th rowspan="2">SO/MO #</th>
        <th rowspan="2">S/Mark</th>
        <th rowspan="2">Sales Owner</th>
        <th rowspan="2">DLT Date</th>
        <th rowspan="2">Throughput Performance</th>
        <th colspan="2">Completion (%)</th>
        <th rowspan="2" style="width:5%;">OutStanding Process Time (hr)</th>

    </tr>
    <tr>
        <th>Warehouse</th>
        <th>Production</th>
    </tr>
                    </thead>
                    <tbody>
                        @php
                        $totalmo = 0;
                        $totalso = 0;

                        $percentageprod = 0;
                        $percentagewh = 0;
						
						$totallinesprd = 0;
						$totallinesstk = 0;

                        @endphp
                        @foreach($results as $result)

                        @php
                  

if (strpos($result->SO_ID, 'SO') !== false) {
    ++$totalso;
} else {
    ++$totalmo;  
}
						
  if($result->TotalSTKQty > 0){
		++$totallinesstk;				
	}
						
	if($result->TotalPRDQty > 0){
		++$totallinesprd;				
	}
						
						
$stk = intval($result->completion_STK);
$prd = intval($result->completion_PRD);
$percentageprod += $prd;
$percentagewh +=  $stk;
                        @endphp
                        <tr>
                            <td><a href="https://pm.ierp.tk/generatePTP?id={{ auth()->user()->id}}&so={{ $result->SO_ID }}" target="_blank" class="viewso"  
                          >{{ $result->SO_ID }}</a></td>
                            <td>{{ $result->AR_NAMES }}</td>
                            <td>{{ $result->MKT_OWNER }}</td>
							    @if ($result->DLT)
                            <td> <span style="display:none;">{{$result->DLT}}</span>{{ \Carbon\Carbon::parse($result->DLT)->format('d-m-Y') }}</td>
							@else
							  <td> <span style="display:none;">-</span>-</td>
							@endif
							

							@if($result->DiffDay == '')
							  <td style="background-color: blue; color:white; text-align:center; font-weight:bold;">Action Required (DLT Date)</td>
                            @elseif( $result->DiffDay <= 2)
                            <td style="background-color: red; color:white; text-align:center; font-weight:bold;">Urgent ( {{ $result->DiffDay }} @if($result->DiffDay > 1 || $result->DiffDay < -1) days @else day @endif)</td>
                            @elseif( $result->DiffDay >= 3 && $result->DiffDay <= 5)
                            <td style="background-color: yellow; color:black; text-align:center; font-weight:bold;">Attention ( {{ $result->DiffDay }} @if($result->DiffDay > 1 || $result->DiffDay < -1) days @else day @endif)</td>
                            @elseif( $result->DiffDay > 5)
                            <td style="background-color: green; color:white; text-align:center; font-weight:bold;">{{ $result->DiffDay }} @if($result->DiffDay > 1 || $result->DiffDay < -1) days @else day @endif</td>
                            @endif
                

                            @if($result->TotalSTKQty > 0)
                            <td>

            @php
        
            if($stk <= 80){
            $classnamestk = 'danger';
            }else if($stk >= 80 && $stk <= 99){
                $classnamestk = 'warning';
            }else if($stk >= 100){
                $classnamestk = 'success';
            }
            @endphp
								
								
                                
                            <div class="progress"
								 	  data-bs-toggle="tooltip"
                        data-bs-offset="0,4"
                        data-bs-placement="left"
                        data-bs-html="true"
                        title="{{ intval($result->completion_STK) }}%">
    <div class="progress-bar progress-bar-striped progress-bar-animated bg-{{$classnamestk}}"
        role="progressbar"
        style="width: {{ intval($result->completion_STK) }}%;"
        aria-valuenow="{{ intval($result->completion_STK) }}"
	
        aria-valuemin="0"
        aria-valuemax="100">
        {{ intval($result->completion_STK) }}%
    </div>
</div>

                </td>
                @else
                <td style="background-color: #D3D3D3; color:black;">-</td>
                @endif

                @if($result->TotalPRDQty > 0)
                            <td>
                            @php
   

            if($prd <= 80){
            $classnameprd = 'danger';
            }else if($prd >= 80 && $prd <= 99){
                $classnameprd = 'warning';
            }else if($prd >= 100){
                $classnameprd = 'success';
            }

            @endphp
                                
                            <div class="progress"
								  data-bs-toggle="tooltip"
                        data-bs-offset="0,4"
                        data-bs-placement="left"
                        data-bs-html="true"
                        title="{{ intval($result->completion_PRD) }}%">
    <div class="progress-bar progress-bar-striped progress-bar-animated bg-{{$classnameprd}}"
        role="progressbar"
        style="width: {{ intval($result->completion_PRD) }}%;"
        aria-valuenow="{{ intval($result->completion_PRD) }}"
        aria-valuemin="0"
        aria-valuemax="100">
        {{ intval($result->completion_PRD) }}%
    </div>
</div>

                       
                            </td>
                            @else
                            <td style="background-color: #D3D3D3; color:black;">-</td>
                @endif

                            <td>{{ $result->OUTSTANDING_PROCESS_TIME }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
              <!--/ Basic Bootstrap Table -->
            </div>
            <!-- / Content -->

         

            @include('Modal.orderingSoModal')
            @include('Navigation.footer')

            <script src="https://cdn.datatables.net/2.0.0/js/dataTables.js"></script>
   
            

<script>
$(document).ready(function() {

$('#myTable').DataTable({
});

var totalso = <?php echo json_encode($totalso) ?>;
var totalmo = <?php echo json_encode($totalmo) ?>;
var prodperc =  <?php echo json_encode($percentageprod) ?>;
var whperc =  <?php echo json_encode($percentagewh) ?>;
var totallinesstk =  <?php echo json_encode($totallinesstk) ?>;
var totallinesprd =  <?php echo json_encode($totallinesprd) ?>;

$('#totalmo').text(totalmo);
$('#totalso').text(totalso);
console.log(totallinesstk);

$('#prodperc').text(Math.round((prodperc / (100 *totallinesprd)) * 100)+ '%');

$('#whperc').text(Math.round((whperc / (100 * totallinesstk)) * 100)+ '%');

});
         </script>


