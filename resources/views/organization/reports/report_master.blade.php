@extends('layouts.organization.app')
@section('content')
 
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header">
                        <h5 class="">Report Master</h5>
                    </div>
                <div class="card-body">
                   
        <div class="row" style="border: 1px solid; color: #f3f3f3; margin: 20px;">
            <div class="col-sm-4">
                <button id="overall_reports" class="btn btn-primary" style="width: 250px; margin-bottom: 7px; " type="button"> Overall Employees Report</button>      
                <button id="employeewise_reports" class="btn btn-primary" style="width: 250px; margin-bottom: 7px" type="button">Employee Wise Report</button>
                <button id="absentee_attendence_reports" class="btn btn-primary" style="width: 250px; margin-bottom: 7px" type="button">Absentee Report</button>
                <button id="forget_attendence_reports" class="btn btn-primary" style="width: 250px; margin-bottom: 7px" type="button">Forget Attendance Report</button>
            </div>
            <div class="col-sm-4">
                <button id="late_comers_reports" class="btn btn-primary" style="width: 250px; margin-bottom: 7px" type="button">Late Comers Report </button>
                <button id="leave_reports" class="btn btn-primary" style="width: 250px; margin-bottom: 7px" type="button">Leave Report</button>
                <button id="intimation_reports" class="btn btn-primary" style="width: 250px; margin-bottom: 7px" type="button">Intimation Report</button>
                <button id="monthly_reports" class="btn btn-primary" style="width: 250px; margin-bottom: 7px" type="button">
                    Monthly Report</button>
            </div>  
            <div class="col-sm-4">
                <button id="daily_attendence_reports" class="btn btn-primary" style="width: 250px; margin-bottom: 7px" type="button">Daily Report</button>
                <button id="quarterly_reports" class="btn btn-primary" style="width: 250px; margin-bottom: 7px" type="button">Quarterly Report</button>
                <button id="employee_performance_reports" class="btn btn-primary" style="width: 250px; margin-bottom: 7px" type="button">Employee Performance Report</button> 
                <button id="custom_reports" class="btn btn-primary" style="width: 250px; margin-bottom: 7px" type="button">Custom Report Builder</button>
   
            </div>                             
       </div>


        <!-- =============================CARD BODY END================================= -->
        <div class="row">

        <!-- ========================= START OVERALL REPORT============================= --> 
        <div class="col-12 stretch-card overall_report" style="display:none;">
            <div class="card">
                <div class="card-header card-height">
                    <div class="row">
                        <div class="col-md-12 col-12">
                            <h5 class="" id="getCameraSerialNumbers">Overall Employee Report</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table id="overall_report_datatable" class="display" style="width:100%">
                        <thead>
                            <tr>
                                <th>Sr No.</th>
                                <th>Employee Name</th>
                                <th>Office Name</th>
                                <th>Department Name</th>
                                <th>Shift Name</th>
                                <th>In Time</th>
                                <th>Out Time</th>
                                <th>Total Time</th>
                                <th>In Image</th>
                                <th>Out Image</th>
                               <!--  <th>Created At</th> -->
                            </tr>
                        </thead>
                        <tbody>
                        <?php $sr_no=1;?>
                            @if(!empty($overallreport))
                            @foreach($overallreport as $overall_report)
                            <tr>
                                <td>{{$sr_no++}}</td>
                                <td>{{$overall_report->name}}</td>
                                <td>{{$overall_report->office_name}}</td>
                                <td>{{$overall_report->department_name}}</td>
                                <td>{{$overall_report->shift_name}}</td>
                                <td>{{$overall_report->in_time}}</td>
                                <td>{{$overall_report->out_time}}</td>
                                <td>{{$overall_report->total_time}}</td>
                                <td>@if(!empty($overall_report->in_image))<img src="{{asset('employee/attendance/')}}/{{$overall_report->in_image}}" class="img-responsive" height="50px;" width="50px;"> @else NA @endif </td>
                                <td>@if(!empty($overall_report->out_image))
                                    <img src="{{asset('employee/attendance/')}}/{{$overall_report->out_image}}" class="img-responsive" height="50px;" width="50px;"> @else NA @endif </td>
                               <!--  <td>{{($overall_report->created_at)}}</td> -->
                            </tr>
                            @endforeach
                            @endif
                        </tbody>    
                    </table>
                </div>
            </div>
        </div>
        <!-- ========================= END OVERALL REPORT  ============================= --> 

        <!-- ======================START LATECOMERS REPORT============================= -->
        <div class="col-12 stretch-card late_comers_report" style="display:none;">
            <div class="card">
                <div class="card-header card-height">
                    <div class="row">
                        <div class="col-md-12 col-12">
                            <h5 class="" id="getCameraSerialNumbers">Late Comers Report</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table id="late_comers_report_datatable" class="display" style="width:100%">
                        <thead>
                            <tr>
                                <th>Sr No.</th>
                                <th>Employee Name</th>
                                <th>Office Name</th>
                                <th>Department Name</th>
                                <th>Shift Name</th>
                                <th>In Time</th>
                                <th>Out Time</th>
                                <th>Total Time</th>
                                <th>In Image</th>
                                <th>Out Image</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $sr_no=1;?>
                            @if(!empty($latecomersreport))
                            @foreach($latecomersreport as $late_commers_report)
                            <tr>
                                <td>{{$sr_no++}}</td>
                                <td>{{$late_commers_report->name}}</td>
                                <td>{{$late_commers_report->office_name}}</td>
                                <td>{{$late_commers_report->department_name}}</td>
                                <td>{{$late_commers_report->shift_name}}</td>
                                <td>{{$late_commers_report->in_time}}</td>
                                <td>{{$late_commers_report->out_time}}</td>
                                <td>{{$late_commers_report->total_time}}</td>
                                <td>@if(!empty($late_commers_report->in_image))<img src="{{asset('employee/attendance/')}}/{{$late_commers_report->in_image}}" class="img-responsive" height="50px;" width="50px;"> @else NA @endif </td>
                                <td>@if(!empty($late_commers_report->out_image))
                                    <img src="{{asset('employee/attendance/')}}/{{$late_commers_report->out_image}}" class="img-responsive" height="50px;" width="50px;"> @else NA @endif </td>
                                <td>{{$late_commers_report->created_at}}</td>    
                            </tr>
                            @endforeach
                            @endif
                        </tbody>    
                    </table>
                </div>
            </div>
        </div>
        <!-- ======================END LATECOMERS REPORT=============================== -->

         <!-- ===================START DAILY ATTENDENCE REPORT======================== --> 
        <div class="col-12 stretch-card daily_attendence_report" style="display:none;">
            <div class="card">
                <div class="card-header card-height">
                    <div class="row">
                        <div class="col-md-12 col-12">
                            <h5 class="" id="getCameraSerialNumbers">Daily Attendence Report</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table id="daily_attendence_report_datatable" class="display" style="width:100%">
                        <thead>
                            <tr>
                                <th>Sr No.</th>
                                <th>Employee Name</th>
                                <th>Office Name</th>
                                <th>Department Name</th>
                                <th>Shift Name</th>
                                <th>In Time</th>
                                <th>Out Time</th>
                                <th>Total Time</th>
                                <th>In Image</th>
                                <th>Out Image</th>
                               <!--  <th>Created At</th> -->
                            </tr>
                        </thead>
                        <tbody>
                        <?php $sr_no=1;?>
                            @if(!empty($daily_attenence))
                            @foreach($daily_attenence as $daily_attenence_records)
                            <tr>
                                <td>{{$sr_no++}}</td>
                                <td>{{$daily_attenence_records->name}}</td>
                                <td>{{$daily_attenence_records->office_name}}</td>
                                <td>{{$daily_attenence_records->department_name}}</td>
                                <td>{{$daily_attenence_records->shift_name}}</td>
                                <td>{{$daily_attenence_records->in_time}}</td>
                                <td>{{$daily_attenence_records->out_time}}</td>
                                <td>{{$daily_attenence_records->total_time}}</td>
                                <td>@if(!empty($daily_attenence_records->in_image))<img src="{{asset('employee/attendance/')}}/{{$daily_attenence_records->in_image}}" class="img-responsive" height="50px;" width="50px;"> @else NA @endif </td>
                                <td>@if(!empty($daily_attenence_records->out_image))
                                    <img src="{{asset('employee/attendance/')}}/{{$daily_attenence_records->out_image}}" class="img-responsive" height="50px;" width="50px;"> @else NA @endif </td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>    
                    </table>
                </div>
            </div>
        </div>
        <!-- ===================END DAILY ATTENDENCE REPORT======================== -->

        <!-- ===================START EMPLOYEEWISE ATTENDENCE REPORT =================== --> 
        <div class="col-12 stretch-card employeewise_report" style="display:none;">
            <div class="card">
                <div class="card-header card-height">
                    <div class="row">
                        <div class="col-md-12 col-12">
                            <h5 class="" id="getCameraSerialNumbers">Employeewise Report</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table id="employeewise_report_datatable" class="display" style="width:100%">
                        <thead>
                            <tr>
                                <th>Sr No.</th>
                                <th>Employee Name</th>
                                <th>Office Name</th>
                                <th>Department Name</th>
                                <th>Shift Name</th>
                                <th>In Time</th>
                                <th>Out Time</th>
                                <th>Total Time</th>
                                <th>In Image</th>
                                <th>Out Image</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>    
                    </table>
                </div>
            </div>
        </div>
        <!-- ===================END EMPLOYEEWISE ATTENDENCE REPORT===================== -->

        <!-- ===================START ABSENTEE ATTENDENCE REPORT ====================== --> 
        <div class="col-12 stretch-card absentee_attendence_report" style="display:none;">
            <div class="card">
                <div class="card-header card-height">
                    <div class="row">
                        <div class="col-md-12 col-12">
                            <h5 class="" id="getCameraSerialNumbers">Absentee Report</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table id="absentee_attenence_report_datatable" class="display" style="width:100%">
                        <thead>
                            <tr>
                                <th>Sr No.</th>
                                <th>Employee Name</th>
                                <th>Office Name</th>
                                <th>Department Name</th>
                                <th>Shift Name</th>
                                <th>In Time</th>
                                <th>Out Time</th>
                                <th>Total Time</th>
                                <th>In Image</th>
                                <th>Out Image</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>    
                    </table>
                </div>
            </div>
        </div>
        <!-- ===================END ABSENTEE ATTENDENCE REPORT ======================== -->
        
        <!-- ===================START FORGET ATTENDENCE REPORT ======================== --> 
        <div class="col-12 stretch-card forget_attendence_report" style="display:none;">
            <div class="card">
                <div class="card-header card-height">
                    <div class="row">
                        <div class="col-md-12 col-12">
                            <h5 class="" id="getCameraSerialNumbers">Forget Attendence Report</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table id="forget_attendence_report_datatable" class="display" style="width:100%">
                        <thead>
                            <tr>
                                <th>Sr No.</th>
                                <th>Employee Name</th>
                                <th>Office Name</th>
                                <th>Department Name</th>
                                <th>Shift Name</th>
                                <th>In Time</th>
                                <th>Out Time</th>
                                <th>Total Time</th>
                                <th>In Image</th>
                                <th>Out Image</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>    
                    </table>
                </div>
            </div>
        </div>
        <!-- ===================END FORGET ATTENDENCE REPORT ========================== -->

        <!-- ======================START LEAVE  REPORT =============================== --> 
        <div class="col-12 stretch-card leave_report" style="display:none;">
            <div class="card">
                <div class="card-header card-height">
                    <div class="row">
                        <div class="col-md-12 col-12">
                            <h5 class="" id="getCameraSerialNumbers">Leave Report</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table id="leave_report_datatable" class="display" style="width:100%">
                        <thead>
                            <tr>
                                <th>Sr No.</th>
                                <th>Employee Name</th>
                                <th>Office Name</th>
                                <th>Department Name</th>
                                <th>Shift Name</th>
                                <th>In Time</th>
                                <th>Out Time</th>
                                <th>Total Time</th>
                                <th>In Image</th>
                                <th>Out Image</th>
                            </tr>
                        </thead>
                        <tbody>
                         
                        </tbody>    
                    </table>
                </div>
            </div>
        </div>
        <!-- ===============================END LEAVE REPORT========================== -->
        
        <!-- ======================START INTIMATION  REPORT ========================= --> 
        <div class="col-12 stretch-card intimation_report" style="display:none;">
            <div class="card">
                <div class="card-header card-height">
                    <div class="row">
                        <div class="col-md-12 col-12">
                            <h5 class="" id="getCameraSerialNumbers">Intimation Report</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table id="intimation_report_datatable" class="display" style="width:100%">
                        <thead>
                            <tr>
                                <th>Sr No.</th>
                                <th>Employee Name</th>
                                <th>Office Name</th>
                                <th>Department Name</th>
                                <th>Shift Name</th>
                                <th>In Time</th>
                                <th>Out Time</th>
                                <th>Total Time</th>
                                <th>In Image</th>
                                <th>Out Image</th>
                            </tr>
                        </thead>
                        <tbody>
                         
                        </tbody>    
                    </table>
                </div>
            </div>
        </div>
        <!-- ===============================END LEAVE REPORT========================== -->    

        <!-- ======================START QUATERLY  REPORT =========================== --> 
        <div class="col-12 stretch-card quarterly_report" style="display:none;">
            <div class="card">
                <div class="card-header card-height">
                    <div class="row">
                        <div class="col-md-12 col-12">
                            <h5 class="" id="getCameraSerialNumbers">Quaterly Report</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table id="quarterly_report_datatable" class="display" style="width:100%">
                        <thead>
                            <tr>
                                <th>Sr No.</th>
                                <th>Employee Name</th>
                                <th>Office Name</th>
                                <th>Department Name</th>
                                <th>Shift Name</th>
                                <th>In Time</th>
                                <th>Out Time</th>
                                <th>Total Time</th>
                                <th>In Image</th>
                                <th>Out Image</th>
                            </tr>
                        </thead>
                        <tbody>
                         
                        </tbody>    
                    </table>
                </div>
            </div>
        </div>
        <!-- ======================END QUATERLY REPORT=============================== -->

        <!-- ======================START EMPLOYEE PERFORMANCE  REPORT =============== --> 
        <div class="col-12 stretch-card employee_performance_report" style="display:none;">
            <div class="card">
                <div class="card-header card-height">
                    <div class="row">
                        <div class="col-md-12 col-12">
                            <h5 class="" id="getCameraSerialNumbers">Employee Performance Report</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table id="employee_performance_report_datatable" class="display" style="width:100%">
                        <thead>
                            <tr>
                                <th>Sr No.</th>
                                <th>Employee Name</th>
                                <th>Office Name</th>
                                <th>Department Name</th>
                                <th>Shift Name</th>
                                <th>In Time</th>
                                <th>Out Time</th>
                                <th>Total Time</th>
                                <th>In Image</th>
                                <th>Out Image</th>
                            </tr>
                        </thead>
                        <tbody>
                         
                        </tbody>    
                    </table>
                </div>
            </div>
        </div>
        <!-- ======================END EMPLOYEE PERFORMANCE REPORT=================== -->

        <!-- ===================START MONTHLY REPORT================================= --> 
        <div class="col-12 stretch-card monthly_report" style="display:none;">
            <div class="card">
                <div class="card-header card-height">
                    <div class="row">
                        <div class="col-md-12 col-12">
                            <h5 class="" id="getCameraSerialNumbers">Monthly Report</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table id="monthly_report_datatable" class="display" style="width:100%">
                        <thead>
                            <tr>
                                <th>Sr No.</th>
                                <th>Employee Name</th>
                                <th>Office Name</th>
                                <th>Department Name</th>
                                <th>Shift Name</th>
                                <th>In Time</th>
                                <th>Out Time</th>
                                <th>Total Time</th>
                                <th>In Image</th>
                                <th>Out Image</th>
                               
                            </tr>
                        </thead>
                        <tbody>
                        
                        </tbody>    
                    </table>
                </div>
            </div>
        </div>
        <!-- ===================END MONTHLY REPORT================================= -->

        <!-- ======================START CUSTOM REPORT ============================== --> 
        <div class="col-12 stretch-card custom_report" style="display:none;">
            <div class="card">
                <div class="card-header card-height">
                    <div class="row">
                        <div class="col-md-12 col-12">
                            <h5 class="" id="getCameraSerialNumbers">Custom Report</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table id="custom_report_datatable" class="display" style="width:100%">
                        <thead>
                            <tr>
                                <th>Sr No.</th>
                                <th>Employee Name</th>
                                <th>Office Name</th>
                                <th>Department Name</th>
                                <th>Shift Name</th>
                                <th>In Time</th>
                                <th>Out Time</th>
                                <th>Total Time</th>
                                <th>In Image</th>
                                <th>Out Image</th>
                            </tr>
                        </thead>
                        <tbody>
                         
                        </tbody>    
                    </table>
                </div>
            </div>
        </div>
        <!-- ======================END CUSTOM REPORT================================ -->
        
    </div>

                </div>
                </div>
            </div>
        </div>

    <!-- ========END MAIN========= -->
    </div>
</div>




<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>

<script>
$(document).ready(function() {
//alert('call');

 $('#overall_report_datatable').DataTable( {  
        dom: 'Bfrtip',
        buttons: ['excel', 'pdf', 'print','copy','csv'] ,
        "bPaginate": true
 });





 $('#daily_attendence_report_datatable').DataTable( {  
        dom: 'Bfrtip',
        buttons: ['excel', 'pdf', 'print','copy','csv'] ,
        "bPaginate": true
 });



 $('#employeewise_report_datatable').DataTable( {  
        dom: 'Bfrtip',
        buttons: ['excel', 'pdf', 'print','copy','csv'] ,
        "bPaginate": true
 });


 $('#absentee_attenence_report_datatable').DataTable( {  
        dom: 'Bfrtip',
        buttons: ['excel', 'pdf', 'print','copy','csv'] ,
        "bPaginate": true
 });

 $('#forget_attendence_report_datatable').DataTable( {  
        dom: 'Bfrtip',
        buttons: ['excel', 'pdf', 'print','copy','csv'] ,
        "bPaginate": true
 });

 $('#late_comers_report_datatable').DataTable( {  
        dom: 'Bfrtip',
        buttons: ['excel', 'pdf', 'print','copy','csv'] ,
        "bPaginate": true
 });


 $('#leave_report_datatable').DataTable( {  
        dom: 'Bfrtip',
        buttons: ['excel', 'pdf', 'print','copy','csv'] ,
        "bPaginate": true
 });

 $('#intimation_report_datatable').DataTable( {  
        dom: 'Bfrtip',
        buttons: ['excel', 'pdf', 'print','copy','csv'] ,
        "bPaginate": true
 });

 $('#monthly_report_datatable').DataTable( {  
        dom: 'Bfrtip',
        buttons: ['excel', 'pdf', 'print','copy','csv'] ,
        "bPaginate": true
 });



 $('#quarterly_report_datatable').DataTable( {  
        dom: 'Bfrtip',
        buttons: ['excel', 'pdf', 'print','copy','csv'] ,
        "bPaginate": true
 });

 $('#employee_performance_report_datatable').DataTable( {  
        dom: 'Bfrtip',
        buttons: ['excel', 'pdf', 'print','copy','csv'] ,
        "bPaginate": true
 });

 $('#custom_report_datatable').DataTable( {  
        dom: 'Bfrtip',
        buttons: ['excel', 'pdf', 'print','copy','csv'] ,
        "bPaginate": true
 });

 





});   
</script>


<script>
$(document).ready(function() {
    $("#overall_reports").click(function(){ 
        $('.overall_report').show();
        $('.late_comers_report').hide();
        $('.employeewise_report').hide(); 
        $('.absentee_attendence_report').hide();  
        $('.forget_attendence_report').hide();  
        $('.leave_report').hide();
        $('.intimation_report').hide();
        $('.monthly_report').hide();
        $('.daily_attendence_report').hide(); 
        $('.quarterly_report').hide(); 
        $('.employee_performance_report').hide(); 
        $('.custom_report').hide();   
    });

 

    $("#late_comers_reports").click(function(){ 
        $('.overall_report').hide();
        $('.late_comers_report').show();
        $('.employeewise_report').hide(); 
        $('.absentee_attendence_report').hide();  
        $('.forget_attendence_report').hide();  
        $('.leave_report').hide();
        $('.intimation_report').hide();
        $('.monthly_report').hide();
        $('.daily_attendence_report').hide(); 
        $('.quarterly_report').hide(); 
        $('.employee_performance_report').hide(); 
        $('.custom_report').hide();   
    });

    $("#employeewise_reports").click(function(){ 
        $('.overall_report').hide();
        $('.late_comers_report').hide();
        $('.employeewise_report').show(); 
        $('.absentee_attendence_report').hide();  
        $('.forget_attendence_report').hide();  
        $('.leave_report').hide();
        $('.intimation_report').hide();
        $('.monthly_report').hide();
        $('.daily_attendence_report').hide(); 
        $('.quarterly_report').hide(); 
        $('.employee_performance_report').hide(); 
        $('.custom_report').hide();   
    });

    $("#absentee_attendence_reports").click(function(){ 
        $('.overall_report').hide();
        $('.late_comers_report').hide();
        $('.employeewise_report').hide(); 
        $('.absentee_attendence_report').show();  
        $('.forget_attendence_report').hide();  
        $('.leave_report').hide();
        $('.intimation_report').hide();
        $('.monthly_report').hide();
        $('.daily_attendence_report').hide(); 
        $('.quarterly_report').hide(); 
        $('.employee_performance_report').hide(); 
        $('.custom_report').hide();   
    });

    $("#forget_attendence_reports").click(function(){ 
        $('.overall_report').hide();
        $('.late_comers_report').hide();
        $('.employeewise_report').hide(); 
        $('.absentee_attendence_report').hide();  
        $('.forget_attendence_report').show();  
        $('.leave_report').hide();
        $('.intimation_report').hide();
        $('.monthly_report').hide();
        $('.daily_attendence_report').hide(); 
        $('.quarterly_report').hide(); 
        $('.employee_performance_report').hide(); 
        $('.custom_report').hide();   
    });

    $("#leave_reports").click(function(){  //alert('leave_reports');
        $('.overall_report').hide();
        $('.late_comers_report').hide();
        $('.employeewise_report').hide(); 
        $('.absentee_attendence_report').hide();  
        $('.forget_attendence_report').hide();  
        $('.leave_report').show();
        $('.intimation_report').hide();
        $('.monthly_report').hide();
        $('.daily_attendence_report').hide(); 
        $('.quarterly_report').hide(); 
        $('.employee_performance_report').hide(); 
        $('.custom_report').hide();   
    });

    $("#intimation_reports").click(function(){ 
        $('.overall_report').hide();
        $('.late_comers_report').hide();
        $('.employeewise_report').hide(); 
        $('.absentee_attendence_report').hide();  
        $('.forget_attendence_report').hide();  
        $('.leave_report').hide();
        $('.intimation_report').show();
        $('.monthly_report').hide();
        $('.daily_attendence_report').hide(); 
        $('.quarterly_report').hide(); 
        $('.employee_performance_report').hide(); 
        $('.custom_report').hide();   
    });

    $("#monthly_reports").click(function(){ 
        $('.overall_report').hide();
        $('.late_comers_report').hide();
        $('.employeewise_report').hide(); 
        $('.absentee_attendence_report').hide();  
        $('.forget_attendence_report').hide();  
        $('.leave_report').hide();
        $('.intimation_report').hide();
        $('.monthly_report').show();
        $('.daily_attendence_report').hide(); 
        $('.quarterly_report').hide(); 
        $('.employee_performance_report').hide(); 
        $('.custom_report').hide();   
    });

    $("#daily_attendence_reports").click(function(){  
        $('.overall_report').hide();
        $('.late_comers_report').hide();
        $('.employeewise_report').hide(); 
        $('.absentee_attendence_report').hide();  
        $('.forget_attendence_report').hide();  
        $('.leave_report').hide();
        $('.intimation_report').hide();
        $('.monthly_report').hide();
        $('.daily_attendence_report').show(); 
        $('.quarterly_report').hide(); 
        $('.employee_performance_report').hide(); 
        $('.custom_report').hide();   
    });

    $("#quarterly_reports").click(function(){ 
        $('.overall_report').hide();
        $('.late_comers_report').hide();
        $('.employeewise_report').hide(); 
        $('.absentee_attendence_report').hide();  
        $('.forget_attendence_report').hide();  
        $('.leave_report').hide();
        $('.intimation_report').hide();
        $('.monthly_report').hide();
        $('.daily_attendence_report').hide(); 
        $('.quarterly_report').show(); 
        $('.employee_performance_report').hide(); 
        $('.custom_report').hide();   
    });

    $("#employee_performance_reports").click(function(){ 
        $('.overall_report').hide();
        $('.late_comers_report').hide();
        $('.employeewise_report').hide(); 
        $('.absentee_attendence_report').hide();  
        $('.forget_attendence_report').hide();  
        $('.leave_report').hide();
        $('.intimation_report').hide();
        $('.monthly_report').hide();
        $('.daily_attendence_report').hide(); 
        $('.quarterly_report').hide(); 
        $('.employee_performance_report').show(); 
        $('.custom_report').hide();   
    });

    $("#custom_reports").click(function(){ 
        $('.overall_report').hide();
        $('.late_comers_report').hide();
        $('.employeewise_report').hide(); 
        $('.absentee_attendence_report').hide();  
        $('.forget_attendence_report').hide();  
        $('.leave_report').hide();
        $('.intimation_report').hide();
        $('.monthly_report').hide();
        $('.daily_attendence_report').hide(); 
        $('.quarterly_report').hide(); 
        $('.employee_performance_report').hide(); 
        $('.custom_report').show();   
    });

 

});
</script>

<script type="text/javascript">
         $('#office_id').change(function(){  
            var office_id = $('#office_id').val();
             if($(office_id).val()!=''){    
                $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
                type: 'POST',
                url : "{{url('ajax/get-emp-office')}}", 
                data: {id:office_id},
                async : false,
                success:function(xhr){  
                if(xhr.status==200){ 
                $('#department_id').prop('selectedIndex',0);   
                var datas = xhr.data;
                $('#department_id').append('');
                for (var i = 0; i < datas.length; i++) {
                $('#department_id').append('<option value="'+datas[i].id+'" data-id="'+datas[i].id+'">'+datas[i].department_name+'</option>');
                }
              }
            }
        });

    }
     
});

</script>


<script type="text/javascript">
         $('#department_id').change(function(){  
            var department_id = $('#department_id').val();
            //$('#department_id').prop('selectedIndex',0);   
             if($(department_id).val()!=''){  
                $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
                type: 'POST',
                url : "{{url('ajax/get-emp-department')}}", 
                data: {department_id:department_id},
                async : false,
                success:function(xhr){  
                if(xhr.status==200){ 
                var datas = xhr.data;
                $('#department_id').append('');
                for (var i = 0; i < datas.length; i++) {
                $('#department_id').append('<option value="'+datas[i].id+'" data-id="'+datas[i].id+'">'+datas[i].department_name+'</option>');
                }
              }
            }
        });

    }
     
});

</script>


<script>
function autoLoad() {  
			$.ajax({
				url: 'http://localhost:8080/hphc_hrms/reportSync',
				cache: false,
				success: function(data) {
					$('#show').html(data);
					console.log('Report was synched');
				}
			});
		}
		$(document).ready(function() {
			//autoLoad();
			setInterval(autoLoad, 90000);
		});


</script>


<script type="text/javascript">
    $(function () {
        $("#name").keypress(function (e) {
            if(e.which === 32) 
                return true;
            var keyCode = e.keyCode;
            $("#letterNameError").html("");
            var regex = /^[A-Za-z]+$/;
            var isValid = regex.test(String.fromCharCode(keyCode));
            if (!isValid) {
                $("#letterNameError").html("Only Alphabets allowed.");
            }
            return isValid;
        });
    });
</script>
 
@endsection('content')
