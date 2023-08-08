@extends('layouts.organization.app')
@section('content')
<style>.table-hover tbody tr{background: #eaeaf1;}</style>
<?php $user_id = Auth::user()->id;?>
<div class="main-panel">
<div class="content-wrapper">
<div class="row">
<div class="col-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-header">
            <h5 class="">Leave Approval Flow <apan id="flow_name_preview"></apan> <apan id="flow_office_preview"></apan> </h5>

            <input type="hidden" name="flow_id" id="flow_id" class="form-control" value="">
            <input type="hidden" name="flow_office_id" id="flow_office_id" class="form-control" value="">

        </div>
        <div class="card-body">
        <div class="card mb-4 leave-flow-data">
            <div class="card" >
            <div style="padding: 7px;">
                <div class="row" >
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Office *</label>
                            <input type="hidden" name="upd_id" class="form-control" value="{{Request::segment(2)}}">
                <select class="form-control" id="office_id" name="office_id" required onchange="get_office_id();">
                    @if(!empty($office))
                    <option value="">--Select--</option>
                    <option value="0" data-id="0">All</option>
                    @foreach($office as $row)
                    <option value="{{$row->id}}" data-id="{{$row->id}}" @if(!empty($update->office_id)) @if($update->office_id==$row->id) selected @endif @endif>{{$row->office_name}}</option>
                    @endforeach
                    @endif
                </select>
                    </div>
                    </div>
                    <div class="col-sm-3"  id="department_select">
                        <div class="form-group">
                            <label>Department *</label>
                <select class="form-control" id="department_id" name="department_id" required onchange="get_designation();">
                    @if(!empty($department))
                                <option value="">--Select--</option>
                                <option value="0" data-id="0">All</option>
                                    @foreach($department as $row1)
                                        <option value="{{$row1->id}}" data-id="{{$row1->id}}" @if(!empty($update->department_id)) @if($update->department_id==$row1->id) selected @endif @endif>{{$row1->department_name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                <div class="col-sm-3" id="designation_select">
                    <div class="form-group">
                        <label>Designation *</label>
                        <select class="form-control" id="position_id" name="position_id" required>
                            @if(!empty($position))
                            <option value="">Select Position</option>
                                @foreach($position as $row2)
                                    <option value="{{$row2->id}}" data-id="{{$row2->id}}" @if(!empty($update->position_id)) @if($update->position_id==$row2->id) selected @endif @endif>{{$row2->position_name}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                    <div class="col-sm-3" id="typeofleave_select">
                        <div class="form-group">
                            <label>Type Of Leave  *</label>
                            <select class="form-control" id="leave_type" name="leave_type" required>
                                <option value="">--Select--</option>
                                @if(!empty($leave_name))
                                    @foreach($leave_name as $row)
                                        <option value="{{$row->id}}">{{$row->emp_type}} &#10148; {{$row->name}} ( {{$row->total_leave}} )</option>
                                    @endforeach
                                @endif
                            </select> 
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12" style="margin-top:-15px;margin-bottom:15px; ">
            <a class="btn btn-success float-right btn-sm mx-1 remove_flow add_flowname_button">&nbsp;+ Add More</a>
            </div>    
        </div>
        </div>
        
        <div class="card mb-4 leave-flow-records">
                <div class="card" >
                    <div class="card-header">
                    <div class="row">
                        <div class="col-md-12">
                            <h5 class="">Leave Flow</h5>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Office/ Organization</th>
                                    <th>Department</th>
                                    <th>Designation</th>
                                    <th>Type Of Leave</th>
                                    <th>Action</th>
                                </tr>
                            </thead>          
                             <tbody id="show_approval_flow"> 
                            </tbody>
                            <?php $sr_no=1;?>
                            
                            <tbody id="show_default_approval_flow"> 
                            @if(!empty($leave_flow_result))
                            @foreach ($leave_flow_result as $leaveFlow)
                                <tr>
                                    <td>{{$sr_no++}}</td>
                                    <td>{{$leaveFlow->office_name}}</td>
                                    <td>{{$leaveFlow->department_name}}</td>
                                    <td>{{$leaveFlow->position_name}} </td>
                                    <td>{{$leaveFlow->emp_type}} ➤ {{$leaveFlow->name}}</td>
                                    <td><a href="{{url('delete-leave-flow')}}/{{$leaveFlow->flow_id}}/{{$leaveFlow->id}}" class="text-danger float-center btn-sm"><i class="fa fa-trash"></i></a>
                                    
                                </tr>
                            @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                </div>
            </div>
           <!-- -------END LEAVE FLOW LISTING ------------- -->     
            <div class="card mb-4 approval-authority-view">
            <div style="padding: 7px;">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Authority Office *</label>
                                 <!-- <input type="text" name="flow_id" class="form-control" value="{{Request::segment(2)}}"> -->
                                 <input type="hidden" name="auth_flow_id" id="auth_flow_id" class="form-control" value="">

                                <select class="form-control" id="authority_office" name="authority_office" required onchange="GetOfficeId()">
                                    @if(!empty($office))
                                        <option value="">--Select--</option>
                                        <option value="0" data-id="0">All</option>
                                        @foreach($office as $row)
                                            <option value="{{$row->id}}" data-id="{{$row->id}}">{{$row->office_name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Department *</label>
                                <select class="form-control" id="authority_department" name="authority_department" required onchange="GetDesignation()">
                                    @if(!empty($department))
                                     
                                        @foreach($department as $row1)
                                            <option value="{{$row1->id}}" @if(!empty($update->department_id)) @if($update->department_id==$row1->id) selected @endif @endif>{{$row1->department_name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Designation *</label>
                                <select class="form-control" id="authority_position" name="authority_position" onchange="GetEmployees();" required>
                                    @if(!empty($position))
                                        <option value="">Select Position</option>
                                        @foreach($position as $row2)
                                            <option value="{{$row2->id}}" @if(!empty($update->position_id)) @if($update->position_id==$row2->id) selected @endif @endif>{{$row2->position_name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label>Select Employee Name</label>
                                <select class="form-control" id="authority_user" name="authority_user" required>
                                    <option value="">--Select--</option>
                                    @if(!empty($users))
                                        @foreach($users as $row3)
                                            <option value="{{$row3->id}}" data-id="{{$row3->id}}"  @if(!empty($update->user_id)) @if($update->user_id==$row3->id) selected @endif @endif>{{$row3->name}} ( {{$row3->employee_code}} )</option>
                                        @endforeach
                                    @endif
                                </select> 
                            </div>
                        </div>
                    </div>

                <div class="col-md-12" style="margin-top:-15px;margin-bottom:15px;">
                <a class="btn btn-success float-right add-more btn-sm remove_approval save_approval">+ Add Approval Authority</a></div>
                </div>
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-12">
                            <h5 class="">Approval Authority</h5>
                        </div>                        
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Office/ Organization</th>
                                <th>Department</th>
                                <th>Designation</th>
                                <th>Employee Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="authority_user_data_view"> 
                        </tbody>
                        <tbody id="default_authority_flow"> 
                            @if(!empty($default_authority_flow))
                            @foreach ($default_authority_flow as $authorityFlow)
                                <tr>
                                    <td>{{$sr_no++}}</td>
                                    <td>{{$authorityFlow->flow_name}}</td>
                                    <td>{{$authorityFlow->office_name}}</td>
                                    <td>{{$authorityFlow->department_name}} </td>
                                    <td>{{$authorityFlow->position_name}} ➤ {{$authorityFlow->name}}</td>
                                    <td><a href="{{url('delete-approval-authority')}}/{{$authorityFlow->id}}/{{$authorityFlow->flow_id}}" class="text-danger float-center btn-sm"><i class="fa fa-trash"></i></a>
                                    
                                </tr>
                            @endforeach
                            @endif
                            </tbody>
                        
                    </table>
                      </div>
                
                </div>
            </div>
        <div class="card setting-approval">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="">Setting</h5>
                        </div>
                        <!-- <div class="col-md-6">
                            <a class="btn btn-success float-right add-more btn-sm save_settings">+ Add Approval Authority</a>
                        </div> -->
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Email Notification For Approve</label>
                                <div class="form-check"><label class="switch">
                                    <input name="email_for_approve" id="email_for_approve" type="checkbox" value="email_for_approve" class="email_for_approve"><span class="slider round"></span></label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Email Notification For Reject</label>
                                <div class="form-check"><label class="switch">
                                    <input name="email_for_reject" id="email_for_reject" type="checkbox" value="email_for_reject" class="email_for_reject"><span class="slider round"></span></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>SMS Notification For Approve</label>
                                <div class="form-check"><label class="switch">
                                    <input name="sms_for_approve" id="sms_for_approve" type="checkbox" value="sms_for_approve" class="sms_for_approve"><span class="slider round"></span></label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>SMS Notification For Reject</label>
                                <div class="form-check"><label class="switch">
                                    <input name="sms_for_reject" id="sms_for_reject" type="checkbox" value="sms_for_reject" class="sms_for_reject"><span class="slider round"></span></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>App Notification For Approve</label>
                                <div class="form-check"><label class="switch">
                                    <input name="app_for_approve" id="app_for_approve" type="checkbox" value="app_for_approve" class="app_for_approve"><span class="slider round"></span></label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>App Notification For Reject</label>
                                <div class="form-check"><label class="switch">
                                    <input name="app_for_reject" id="app_for_reject" type="checkbox" value="app_for_reject" class="app_for_reject"><span class="slider round"></span></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <a class="btn btn-primary text-center save-settings btn-sm">Save</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@if(!empty($flow_master)) @foreach($flow_master as $flows)
<div class="row">
<div class="col-md-12 settings_flow_list">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <h5 class=""><i class="fa fa-sitemap"></i> {{$flows->flow_name}} ➤
                    <?php 
                    $approval = App\Models\ApprovalFlow::select('office_id')->where('flow_id',$flows->id)->first();
                    if(!empty($approval)){
                        $office = App\Models\OfficeMaster::select('id','office_name')->where('id',$approval->office_id)->first();
                        if(!empty($office)) { echo $office->office_name;}
                    }?>
                    </h5>
                </div>
                <div class="col-md-6">
                    <a class="text-primary float-right btn-sm" data-toggle="modal" data-target="#myModal" onclick="get_flow({{$flows->id}})"><i class="fa fa-eye" style="font-size:18px"></i></a>
                    <a href="{{url('delete-leave-types')}}/{{$flows->id}}" class="text-danger float-right btn-sm"><i class="fa fa-trash" style="font-size:18px"></i></a>
                </div>
            </div>
        </div>
        <div class="card-body" style="display: block;">
            <?php $select = App\Models\NotificationSetting::where('flow_id',$flows->id)->where('flow_type','leave-flow')->first();
            ?>
            <table class="table table-hover">
                <tr>
                    <td><b>Email Notification On Approve :</b> <?php if(!empty($select->email_for_approve)) { if($select->email_for_approve==1){ echo 'Yes';}else{ echo 'No';} } else { echo 'No'; } ?></td>
                   
                    <td><b>Email Notification On Reject :</b> <?php if(!empty($select->email_for_reject)) { if($select->email_for_reject==1){ echo 'Yes';}else{ echo 'No';}} else { echo 'No'; }?></td>
                </tr>
                <tr>
                    <td><b>SMS Notification On Approve :</b> <?php if(!empty($select->sms_for_approve)) { if($select->sms_for_approve==1){ echo 'Yes';}else{ echo 'No';}} else { echo 'No'; }?></td>
                   
                    <td><b>SMS Notification On Reject :</b> <?php if(!empty($select->sms_for_reject)) {if($select->sms_for_reject==1){ echo 'Yes';}else{ echo 'No'; }} else { echo 'No'; }?></td>
                </tr>
                <tr>
                    <td><b>App Notification On Approve :</b> <?php if(!empty($select->app_for_approve)) { if($select->app_for_approve==1){ echo 'Yes';} else{ echo 'No';} } else { echo 'No'; } ?></td>

                    <td><b>App Notification On Reject :</b> <?php if(!empty($select->app_for_reject)) {if($select->app_for_reject==1){ echo 'Yes';}else{ echo 'No';} } else { echo 'No'; } ?></td>
                </tr>
            </table>
        </div>
        <div class="card-footer" style="display: block;"></div>
    </div>
</div>
</div>
@endforeach
@endif
</div>
</div>
<div id="myModal" class="modal fade" role="dialog">
<div class="modal-dialog modal-xl">
<div class="modal-content">
<div class="modal-header">
<h4 class="modal-title">Leave Approval Flow <span id="flow_name_view"></span></h4>
<button type="button" class="close" data-dismiss="modal">&times;</button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Office</th>
                        <th>Department</th>
                        <th>Designation</th>
                        <th>Type Of Leave</th>
                    </tr>
                </thead>
                <tbody id="approval_flow_data_view"> 
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Office</th>
                        <th>Department</th>
                        <th>Designation</th>
                        <th>Employee Name</th>
                    </tr>
                </thead>
                <tbody id="authority_user_data_view"> 
                </tbody>
            </table>
        </div>
    </div>


<div class="row">
        <div class="col-md-12">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th colspan="6"><h5 align="center">Notifications Settings</h5></th>  
                    </tr>
                    <tr>
                        <th>ID</th>
                        <th>Email On Approve</th>
                        <th>Email On Reject</th>
                        <th>SMS On Approve</th>
                        <th>SMS On Reject</th>
                        <th>App On Approve</th>
                        <th>App On Reject</th>
                    </tr>
                </thead>
                <tbody id="notification_data"> 
                   

                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="modal-footer" id="leave_ids_data">
</div>
</div>
</div>
</div>




<!-- ------------START POPUP FLOW DATA VIEW------------- -->

<div id="get_flow_data" class="modal fade" role="dialog">
<div class="modal-dialog modal-xl">
<div class="modal-content">
<div class="modal-header">
<h4 class="modal-title">Flow Name </h4>
<button type="button" class="close" data-dismiss="modal">&times;</button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Sr No.</th>
                        <th>Flow Name</th>
                        <th>Status</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody id="flowdatalist"> 
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="modal-footer" id="flow_data_ids">
</div>
</div>
</div>
</div>





<!-- ------------END POPUP FLOW DATA VIEW--------------- -->



<!-- ----HTML START LEAVE FLOW MODAL THROUGH AJAX---------- -->

<div id="myModalLeave" class="modal fade" role="dialog">
<div class="modal-dialog modal-xl">
<div class="modal-content">
<div class="modal-header">
<h4 class="modal-title">Leave View Flow <span id="leave_flow_name_view"></span></h4>  <span style="margin-left: 425px; margin-top: 5px;">Created at: <span id="leave_flow_created_at"></span></span> 
<button type="button" class="close" data-dismiss="modal">&times;</button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Office</th>
                        <th>Department</th>
                        <th>Designation</th>
                        <th>Type Of Leave</th>
                    </tr>
                </thead>
                <tbody id="leave_flow_data_view"> 
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Office</th>
                        <th>Department</th>
                        <th>Designation</th>
                        <th>Employee Name</th>
                    </tr>
                </thead>
                <tbody id="leave_user_data_view"> 
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="modal-footer" id="leave_ids">
</div>
</div>
</div>
</div>

<!-- ----HTML END LEAVE FLOW MODAL THROUGH AJAX------------ -->

<!-- ----HTML START AUTHORITY LEAVE FLOW MODAL THROUGH AJAX---------- -->

<div id="myModalAuthority" class="modal fade" role="dialog">
<div class="modal-dialog modal-xl">
<div class="modal-content">
<div class="modal-header">
<h4 class="modal-title"> Authority Approval Flow<span id="authority_flow_name_view"></span></h4>
 <span style="margin-left: 425px; margin-top: 5px;">Created at: <span id="authority_flow_created_at"></span></span> 
<button type="button" class="close" data-dismiss="modal">&times;</button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Office</th>
                        <th>Department</th>
                        <th>Designation</th>
                        <th>Type Of Leave</th>
                    </tr>
                </thead>
                <tbody id="authority_data"> 
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Office</th>
                        <th>Department</th>
                        <th>Designation</th>
                        <th>Employee Name</th>
                    </tr>
                </thead>
                <tbody id="authority_flow_data"> 
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="modal-footer" id="leave_ids">
</div>
</div>
</div>
</div>

<!-- ----HTML END AUTHORITY LEAVE FLOW MODAL THROUGH AJAX------------ -->

<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>

<script>
    $(document).ready(function(){
    var url_data= window.location.href;
    var result=url_data.split("add-approval-flow/");
    var flow_id=(result[1]);
    GetFlowNameRecords(flow_id); 

});

</script>
<!-- -----START TO GET FLOW NAME DATA-------- -->
<script>
function GetFlowNameRecords(flow_id){  
    if(flow_id!=""){  
            $.ajax({
                type: "POST",headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
                url: "{{url('ajax/get-flow-records')}}",
                data:{flow_id: flow_id},
                success: function(xhr) {
                    var datas = xhr.data;
                    $('#flow_name_preview').text('➤ '+datas.flow_name);  
                     $('#flow_id').val(datas.id);
                    spinner.hide();
                }
            });
    }
}    

</script>

<!-- -----END TO GET FLOW NAME DATA--------- -->




<script>
function get_office_id() {  
var flow_office_id = $('#flow_office_id').val();
var department_id = $('#office_id option:selected').data('id');
if(flow_office_id==''){ 
    if($('.remove_flow').hasClass('change-office')){
        $('.remove_flow').addClass('add_flowname_button');
        $('.remove_flow').removeClass('change-office');
        $('#department_select').show(); 
        $('#designation_select').show(); 
        $('#typeofleave_select').show(); 
    }
    OfficeFun(department_id);
}
else if(department_id=='0'){ //alert('call dept');
    OfficeFun(department_id);  
    $('#department_select').hide(); 
    $('#designation_select').hide(); 
    $('#typeofleave_select').hide(); 
}

else if(flow_office_id=='' && department_id=='0'){ //alert('call ofc & dept');
    OfficeFun(department_id);  

}
else{ //alert('Call Default else');
    if(flow_office_id==department_id){
        if($('.remove_flow').hasClass('change-office')){
            $('.remove_flow').addClass('add_flowname_button');
            $('.remove_flow').removeClass('change-office');
            $('#department_select').show(); 
            $('#designation_select').show(); 
            $('#typeofleave_select').show(); 
        }
        OfficeFun(department_id);
    }/*else{ alert('DF Call'); 
        //toastr.error('Office Canot be changed');
        $('.remove_flow').removeClass('add_flowname_button');
        $('.remove_flow').addClass('change-office');
    }*/
}
}
function OfficeFun(department_id){ //alert('call dept id ofc fun'+department_id);
var spinner = $('#loader');
spinner.show();

if(department_id=='0'){
            $('#department_select').hide(); 
            $('#designation_select').hide(); 
            $('#typeofleave_select').hide(); 
        }else{
            $('#department_select').show(); 
            $('#designation_select').show(); 
            $('#typeofleave_select').show(); 
        }


$('#department_id').empty();
$('#designation_id').empty();
$.ajax({
    type: "POST",headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
    url: "{{url('ajax/get-department-name')}}",
    data:{department_id: department_id},
    success: function(xhr) {
        var datas = xhr.data;
        $('#department_id').append('<option value="0" data-id="0"> Select All</option>');
        for (var i = 0; i < datas.length; i++) {
            $('#department_id').append('<option value="'+datas[i].id+'" data-id="'+datas[i].id+'">'+datas[i].department_name+'</option>');
        }spinner.hide();
    }
});
}
function get_designation() {
var spinner = $('#loader');
spinner.show();
var office_id = $('#office_id option:selected').data('id');
var department_id = $('#department_id option:selected').data('id');

// alert('OFC'+office_id+' DEPT '+department_id); 

$('#position_id').empty();
$('#leave_type').empty();
$.ajax({
    type: "POST",headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
    url: "{{url('ajax/get-designation')}}",
    data: {office_id: office_id,department_id: department_id},
    success: function(xhr) {
        var datas = xhr.data;
        $('#position_id').append('<option value="">--Select--</option><option value="0" data-id="0"> --- All ---</option>');
        for (var i = 0; i < datas.length; i++) {
            $('#position_id').append('<option value="'+datas[i].id+'" data-id="'+datas[i].id+'">'+datas[i].position_name+'</option>');
        }spinner.hide();
    }
});
$.ajax({
    type: "POST",
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
    url: "{{url('ajax/get-leave')}}",
    data: {office_id: office_id,department_id: department_id},
    success: function(xhr) {
        var datas = xhr.data;
        $('#leave_type').append('<option value="">--Select--</option>');
        for (var i = 0; i < datas.length; i++) {
            $('#leave_type').append('<option value="'+datas[i].id+'">'+datas[i].emp_type+' &#10148; '+datas[i].name+' ( '+datas[i].total_leave+' )</option>');
        }spinner.hide();
    }
});
}

/*-----Start vikas code --------*/
/*function GetOfficeId() {
var flow_office_id = $('#auth_flow_id').val();
var department_id = $('#authority_office').val();

if(department_id=='0'){  
             OfficeFunAuth(department_id);  
            $('#authority_department').hide(); 
            $('#authority_position').hide(); 
            $('#authority_user').hide();  
        }else{  
            $('#authority_department').show(); 
            $('#authority_position').show(); 
            $('#authority_user').show(); 
            OfficeFunAuth(department_id);  
        }

if(flow_office_id==''){  
    if($('.remove_approval').hasClass('change-office')){
        $('.remove_approval').addClass('save_approval');
        $('.remove_approval').removeClass('change-office');
    }
    OfficeFunAuth(department_id); 
}

else{  
    if(flow_office_id==department_id){
        if($('.remove_approval').hasClass('change-office')){
            $('.remove_approval').addClass('save_approval');
            $('.remove_approval').removeClass('change-office');
            $('#authority_department').show(); 
            $('#authority_position').show(); 
            $('#authority_user').show(); 
        }
        OfficeFunAuth(department_id);
    } 
}

}*/

/*-----End vikas code --------*/



function GetOfficeId() {
            var flow_office_id = $('#flow_office_id').val();
            var department_id = $('#authority_office').val();
            if(flow_office_id==''){
                if($('.remove_approval').hasClass('change-office')){
                    $('.remove_approval').addClass('save_approval');
                    $('.remove_approval').removeClass('change-office');
                }
                OfficeFunAuth(department_id);
            }
            else{
                if(flow_office_id==department_id){
                    if($('.remove_approval').hasClass('change-office')){
                        $('.remove_approval').addClass('save_approval');
                        $('.remove_approval').removeClass('change-office');
                    }
                    OfficeFunAuth(department_id);
                }
                else{
                    toastr.error('Office Canot be changed');
                    $('.remove_approval').removeClass('save_approval');
                    $('.remove_approval').addClass('change-office');
                }
            }
        }


function OfficeFunAuth(department_id){ // alert("DEPT ID"+department_id);
var spinner = $('#loader');
//spinner.show();
$('#authority_department').empty();
$.ajax({
    type: "POST",headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
    url: "{{url('ajax/get-department-name')}}",
    data: {department_id:department_id},
    success: function(xhr) {
        var datas = xhr.data;
        $('#authority_department').append('<option value="">--Select--</option>');
        for (var i = 0; i < datas.length; i++) {
            $('#authority_department').append('<option value="'+datas[i].id+'">'+datas[i].department_name+'</option>');
        }spinner.hide();
    }
});

}
function GetDesignation() {
var spinner = $('#loader');
spinner.show();
var office_id = $('#authority_office').val();
var department_id = $('#authority_department').val();
$('#authority_position').empty();
$.ajax({
    type: "POST",
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
    url: "{{url('ajax/get-designation')}}",
    data: {office_id: office_id,department_id: department_id,},
    success: function(xhr) {
        var datas = xhr.data;
        $('#authority_position').append('<option value="">--Select--</option>');
        for (var i = 0; i < datas.length; i++) {
            $('#authority_position').append('<option value="'+datas[i].id+'" data-id="'+datas[i].id+'">'+datas[i].position_name+'</option>');
        }spinner.hide();
    }
});
}
function GetEmployees(){
var spinner = $('#loader');
spinner.show();
var office_id = $('#authority_office').val();
var department_id = $('#authority_department').val();
var position_id = $('#authority_position').val();
$('#authority_user').empty();
$.ajax({
    type: "POST",
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
    url: "{{url('ajax/get-employee-against-position')}}",
    data: {
        office_id: office_id,
        department_id: department_id,
        position_id: position_id,
    },
    success: function(xhr) {
        var datas = xhr.data;
        $('#authority_user').append('<option value="">--Select--</option>');
        for (var i = 0; i < datas.length; i++) {
            $('#authority_user').append('<option value="'+datas[i].id+'">'+datas[i].name+'</option>');
        }spinner.hide();
    }
});
}
</script>
<script>
$(document).ready(function() {
$(".add_flowname_button").click(function(){   
    $('.show_datas').show();
    $('.add_flowname_button').removeClass('btn-success');
    $('.add_flowname_button').addClass('btn-primary');
    if($('.add_flowname_button').hasClass('btn-primary')){
        var spinner = $('#loader');
        spinner.show();
        var flow_id = $('#flow_id').val();
        var office_id = $('#office_id').val();
        var department_id = $('#department_id').val();
        var position_id = $('#position_id').val();
        var leave_type = $('#leave_type').val();
        if(office_id=='0'){ //alert ('Call Ofc Id'+office_id+" Flow Id"+flow_id); 
            $.ajax({
                type: "POST",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
                url: "{{url('ajax/save-all-approval-flow')}}",
                data: {
                    flow_id: flow_id,
                    office_id: office_id,
                },
                success: function(xhr) {
                    if(xhr.status==200){ 
                        $('#approval_flow_data').empty();
                        var html = '';
                        toastr.success(xhr.msg);
                        $('.after-add-more').hide();
                        $('.add_flowname_button').addClass('btn-success');
                        $('.add_flowname_button').removeClass('btn-primary');
                        var datas = xhr.datas;
                        if(datas.length>0){
                            for (var i = 0; i < datas.length; i++) {
                                html += '<tr><td>'+(i+1)+'</td>'+
                                    '<td>'+datas[i].office_name+'</td>'+
                                    '<td>'+datas[i].department_name+'</td>'+
                                    '<td>'+datas[i].position_name+'</td>'+
                                    '<td>'+datas[i].emp_type+' ➤ '+datas[i].name+'</td>'+
                                    '<td><a class="text-primary" data-toggle="modal" data-target="#myModalLeave" onclick="get_leave_flow('+datas[i].id+')"><i class="fa fa-eye"></i></a>|<a href="{{url("delete-leave-flow")}}/'+datas[i].id+'" class="text-danger delete-button"><i class="fa fa-trash"></i></a></td>'+
                                '</tr>';
                            }
                            $('#approval_flow_data').append(html);
                            spinner.hide();
                        }
                        $('.show_datas').hide();
                        $('.approval-authority-view').show();
                    }else{
                        toastr.error(xhr.msg);
                         $('.approval-authority-view').show();
                    }
                    if(xhr.office.length>0){
                        $('#flow_office_id').val(xhr.office.id);
                        //$('#flow_office_preview').text('➤ '+xhr.office.office_name);
                    }
                    spinner.hide();
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    spinner.hide();
                }
            });
        
            
        }
        else{
             if(office_id!='' && department_id!='' && position_id!='' && leave_type!=''){
            $.ajax({
                type: "POST",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
                url: "{{url('ajax/save-approval-flow')}}",
                data: {
                    flow_id: flow_id,
                    office_id: office_id,
                    department_id: department_id,
                    position_id: position_id,
                    leave_type: leave_type,
                },
                success: function(xhr) { 
                    if(xhr.status==200){ //alert(JSON.stringify(xhr.status));
                        $('#show_approval_flow').empty();
                        var html = '';
                        toastr.success(xhr.msg);
                        $('.after-add-more').hide();
                        $('.add_flowname_button').addClass('btn-success');
                        $('.add_flowname_button').removeClass('btn-primary');
                        var datas = xhr.datas;
                        if(datas.length>0){
                            for (var i = 0; i < datas.length; i++) {
                                html += '<tr><td>'+(i+1)+'</td>'+
                                    '<td>'+datas[i].office_name+'</td>'+
                                    '<td>'+datas[i].department_name+'</td>'+
                                    '<td>'+datas[i].position_name+'</td>'+
                                    '<td>'+datas[i].emp_type+' ➤ '+datas[i].name+'</td>'+
                                    '<td><a href="{{url("delete-leave-flow")}}/'+datas[i].flow_id+'/'+datas[i].id+'" class="text-danger delete-button"><i class="fa fa-trash"></i></a></td>'+
                                '</tr>';
                            }
                            $('#show_approval_flow').append(html);
                            $('#show_default_approval_flow').hide();
                        }
                        $('.show_datas').hide();
                        $('.approval-authority-view').show();
                    }else{
                        toastr.error(xhr.msg);
                         $('.approval-authority-view').show();
                    }
                    if(xhr.office.length>0){
                        $('#flow_office_id').val(xhr.office.id);
                        $('#flow_office_preview').text('➤ '+xhr.office.office_name);
                    }
                    spinner.hide();
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    spinner.hide();
                }
            });
        }else{
            spinner.hide();
            toastr.error('Please enter flow');
        }
        }   
    }
});
});
</script>
<script>
$(document).on('click','.save-settings',function(){
swal({
    title: "Are you sure?",
    text: "Do you want to save ",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#DD6B55",
    confirmButtonText: "Yes",
    closeOnConfirm: false
}, function (isConfirm) {
    if (isConfirm) {
        var flow_id = $('#flow_id').val();
        if ($('#email_for_approve').prop('checked')==true){ 
            var email_for_approve = $('#email_for_approve').val();
        }else{
            var email_for_approve = '';
        }
        if ($('#email_for_reject').prop('checked')==true){ 
            var email_for_reject = $('#email_for_reject').val();
        }else{
            var email_for_reject = '';
        }
        if ($('#sms_for_approve').prop('checked')==true){ 
            var sms_for_approve = $('#sms_for_approve').val();
        }else{
            var sms_for_approve = '';
        }
        if ($('#sms_for_reject').prop('checked')==true){ 
            var sms_for_reject = $('#sms_for_reject').val();
        }else{
            var sms_for_reject = '';
        }
        if ($('#app_for_approve').prop('checked')==true){ 
            var app_for_approve = $('#app_for_approve').val();
        }else{
            var app_for_approve = '';
        }
        if ($('#app_for_reject').prop('checked')==true){ 
            var app_for_reject = $('#app_for_reject').val();
        }else{
            var app_for_reject = '';
        }
        $.ajax({
            type: "POST",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
            url: "{{url('ajax/save-settings')}}",
            data: {
                flow_id: flow_id,
                email_for_approve: email_for_approve,
                email_for_reject: email_for_reject,
                sms_for_approve: sms_for_approve,
                sms_for_reject: sms_for_reject,
                app_for_approve: app_for_approve,
                app_for_reject: app_for_reject,
            },
            success: function(xhr) {
                swal(xhr.msg, "Succesfully "+xhr.msg, "success");
                setTimeout(function () {
                    location.reload();
                }, 1500);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                swal("Something Went to Wrong!", "Please try again", "error");
            }
        });
    }
});
});
$(document).ready(function() {
$(".remove_flow").click(function(){ 
    if($('.remove_flow').hasClass('change-office')){
        toastr.error('Office Canot be changed');
    }
}); 
$(".remove_approval").click(function(){ 
    if($('.remove_approval').hasClass('change-office')){
        toastr.error('Office Canot be changed');
    }
}); 
}); 

$(document).ready(function() {
$(".add-more").click(function(){ //alert('Call Authority Button');
    $('.after-add-more').show();
    $('.save_approval').removeClass('btn-success');
    $('.save_approval').addClass('btn-primary');
    if($('.save_approval').hasClass('btn-primary')){
        var flow_id = $('#flow_id').val();
        var office_id = $('#authority_office').val();
        var department_id = $('#authority_department').val();
        var position_id = $('#authority_position').val();
        var authority_user = $('#authority_user').val();
 //alert("flow_id->"+flow_id+"OFC ID->"+office_id+"DEPT NAME:"+department_id+"POSITION->"+position_id+"Authority->"+authority_user);return false;
    if(office_id=='0'){
            var spinner = $('#loader');
           // spinner.show();
            $.ajax({
                type: "POST",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
                url: "{{url('ajax/save-authority-admin')}}",
                data: {
                    office_id: office_id,
                    flow_id: flow_id,
                },
                success: function(xhr) {
                    if(xhr.status==200){ 
                        //location.reload();
                        $('#authority_user_data').empty();
                        var html = '';
                        toastr.success(xhr.msg);
                        $('.after-add-more').hide();
                        $('.save_approval').addClass('btn-success');
                        $('.save_approval').removeClass('btn-primary');
                        $('#authority_department').empty();
                        $('#authority_position').empty();
                        $('#authority_user').empty();
                        $('#hide_flow_name').hide();

                        var datas = xhr.datas;
                        if(datas.length>0){
                            for (var i = 0; i < datas.length; i++) {
                                html += '<tr><td>'+(i+1)+'</td>'+
                                    '<td>'+datas[i].office_name+'</td>'+
                                    '<td>'+datas[i].department_name+'</td>'+
                                    '<td>'+datas[i].position_name+'</td>'+
                                    '<td>'+datas[i].name+'</td>'+
                                    '<td>BB<a class="text-primary" data-toggle="modal" data-target="#myModalAuthority" onclick="get_authority_flow('+datas[i].flow_id+'/'+datas[i].id+')"><i class="fa fa-eye"></i></a>|<a href="{{url("delete-approval-authority")}}/'+datas[i].id+'/'+datas[i].flow_id+'" class="text-danger delete-button"><i class="fa fa-trash"></i></a></td>'+
                                '</tr>';
                            }
                            $('#authority_user_data').append(html);
                            $('.setting-approval').show();
                        }
                    }else{
                        toastr.error(xhr.msg);
                    } spinner.hide();
                }
            });
        }
       else if(office_id!='' && department_id!='' && position_id!='' && authority_user!=''){
            var spinner = $('#loader');
            spinner.show();
            $.ajax({
                type: "POST",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
                url: "{{url('ajax/save-authority-admin')}}",
                data: {
                    flow_id: flow_id,
                    office_id: office_id,
                    department_id: department_id,
                    position_id: position_id,
                    authority_user: authority_user,
                },
                success: function(xhr) {
                    if(xhr.status==200){

                        location.reload();
                        $('#authority_user_data').empty();
                        var html = '';
                        toastr.success(xhr.msg);
                        $('.after-add-more').hide();
                        $('.save_approval').addClass('btn-success');
                        $('.save_approval').removeClass('btn-primary');
                        $('#authority_department').empty();
                        $('#authority_position').empty();
                        $('#authority_user').empty();
                        var datas = xhr.datas;
                        if(datas.length>0){
                            for (var i = 0; i < datas.length; i++) {
                                html += '<tr><td>'+(i+1)+'</td>'+
                                    '<td>'+datas[i].office_name+'</td>'+
                                    '<td>'+datas[i].department_name+'</td>'+
                                    '<td>'+datas[i].position_name+'</td>'+
                                    '<td>'+datas[i].name+'</td>'+
                                    '<td>BBCD<a class="text-primary" data-toggle="modal" data-target="#myModalAuthority" onclick="get_authority_flow('+datas[i].flow_id+'/'+datas[i].id+')"><i class="fa fa-eye"></i></a>|<a href="{{url("delete-approval-authority")}}/'+datas[i].id+'/'+datas[i].flow_id+'" class="text-danger delete-button"><i class="fa fa-trash"></i></a></td>'+
                                '</tr>';
                            }
                            $('#authority_user_data').append(html);
                            $('.setting-approval').show();
                        }
                    }else{
                        toastr.error(xhr.msg);
                    }spinner.hide();
                }
            });
        }else{
            toastr.error('Please enter approval');
        }
    }
});
});
</script>

<script>
$(document).ready(function() {
$(".add_flow_name").click(function(){ 
    var flow_name = $('#flow_name').val();
    if(flow_name!=''){
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
            type: 'POST',
            url : "{{url('ajax/save-flow-name')}}", 
            data: {flow_name:flow_name},
            success:function(xhr){ 
                $('#approval_flow_data').empty();
                $('#authority_user_data').empty();
                $('.setting-approval').empty();
                $('.leave-flow-data').show();
                $('.leave-flow-records').show();
                $('.setting-approval').show();
                $('.settings_flow_list').show();
                var html = '';
                var html1 = '';
                if(xhr.status==200){  // alert('If Condition');
                    toastr.success(xhr.msg);
                     $('.leave-flow-data').show();
                     $('#flow_id').val(xhr.flow.id); 
                     $('#auth_flow_id').val(xhr.flow.id); 
                     $('.hide_flow_name').hide();
                     $('#default_approval_flow_data').hide();
                     $('.hide_flow_name').hide();
                }else{      // alert('Else Condition'); 
                    toastr.error(xhr.msg);
                    $('.hide_flow_name').show();
                    $('.leave-flow-data').hide();
                    $('.approval-authority-view').hide();
                    $('.leave-flow-records').hide();
                    $('.setting-approval').hide();
                    $('#default_approval_flow_data').hide();
                    $('.settings_flow_list').hide();
                    
                }
                /*if(xhr.office.id!=null){ //alert('BLANK');
                    //$('#flow_office_preview').text('➤ '+xhr.office.office_name);
                    //$('#flow_office_id').val(xhr.office.id);
                }*/
                /*-----------START AJAX DATA LISTING--------------*/    
                /*--------------SHOW FLOW NAME ON TITLE -------------------*/ 
            $('#flow_id').val(xhr.flow.id);
            //$('#flow_name_preview').text('➤ '+xhr.flow.flow_name);
                var datas = xhr.datas;
                if(datas.length>0){
                    for (var i = 0; i < datas.length; i++) {
                        html += '<tr><td>'+(i+1)+'</td>'+
                            '<td>'+datas[i].office_name+'</td>'+
                            '<td>'+datas[i].department_name+'</td>'+
                            '<td>'+datas[i].position_name+'</td>'+
                            '<td>'+datas[i].emp_type+' ➤ '+datas[i].name+'</td>'+
                            '<td>DT-1-<a class="text-primary" data-toggle="modal" data-target="#myModalLeave" onclick="get_leave_flow('+datas[i].id+')"><i class="fa fa-eye"></i></a>|<a href="{{url("delete-leave-flow")}}/'+datas[i].id+'" class="text-danger delete-button"><i class="fa fa-trash"></i></a></td>'+
                        '</tr>';
                    }
                }

                $('#approval_flow_data').append(html);
                if(datas.length>0){
                    $('.approval-authority-view').show();
                    var authorities = xhr.authorities;
                    for (var i = 0; i < authorities.length; i++) {
                        html1 += '<tr><td>'+(i+1)+'</td>'+
                            '<td>'+authorities[i].office_name+'</td>'+
                            '<td>'+authorities[i].department_name+'</td>'+
                            '<td>'+authorities[i].position_name+'</td>'+
                            '<td>'+authorities[i].name+'</td>'+
                            '<td>AAA<a class="text-primary" data-toggle="modal" data-target="#myModalAuthority" data-id="'+authorities[i].flow_id+'" onclick="get_authority_flow('+authorities[i].flow_id+','+authorities[i].id+')"><i class="fa fa-eye"></i></a>|<a href="{{url("delete-approval-authority")}}/'+authorities[i].id+'/'+authorities[i].flow_id+'" class="text-danger delete-button"><i class="fa fa-trash"></i></a></td>'+
                        '</tr>';
                    }
                    $('#authority_user_data').append(html1);

                    $('.setting-approval').show();
                     $('.hide_flow_name').hide();
                    show_datas();
                    spinner.hide();
                }   

                /*------------END AJAX DATA LISTING----------------*/

            }
        });
    }
});
});
</script>
<script>
function show_datas(){
    //$('.hide_flow_name').hide();
}
</script>
<!-- ----GET LEAVE FLOW DATA IN MODAL THROUTH AJAX START --------- -->
<script>
function get_leave_flow(id){  
$('#leave_flow_data_view').empty();
$('#leave_user_data_view').empty();
var spinner = $('#loader');
//spinner.show();
$.ajax({
    type: "POST",
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
    url: "{{url('ajax/get-leave-approval-flow-data')}}",
    data: {id: id},
    success: function(xhr) {
        var html='';
        var html1='';
        if(xhr.status==200){

            var created_at=xhr.flow.created_at;
            const created_at_dt = xhr.flow.created_at.split("T"); 
            

            $('#leave_flow_name_view').text('➤ '+xhr.flow.flow_name);
            $('#leave_flow_created_at').text('➤ '+created_at_dt[0]);

            var datas = xhr.datas;
            for (var i = 0; i < datas.length; i++) {
                html += '<tr><td>'+(i+1)+'</td>'+
                    '<td>'+datas[i].office_name+'</td>'+
                    '<td>'+datas[i].department_name+'</td>'+
                    '<td>'+datas[i].position_name+'</td>'+
                    '<td>'+datas[i].emp_type+' ➤ '+datas[i].name+'</td>'+
                '</tr>';
            }
            var authorities = xhr.authorities;
            for (var i = 0; i < authorities.length; i++) {
                html1 += '<tr><td>'+(i+1)+'</td>'+
                    '<td>'+authorities[i].office_name+'</td>'+
                    '<td>'+authorities[i].department_name+'</td>'+
                    '<td>'+authorities[i].position_name+'</td>'+
                    '<td>'+authorities[i].name+'</td>'+
                '</tr>';
            }
        }
        $('#leave_flow_data_view').append(html);
        $('#leave_user_data_view').append(html1);
        spinner.hide();
    }
});
}
</script>
<!-- ----GET LEAVE FLOW DATA IN MODAL THROUTH AJAX END ----------- -->

<!-- ----GET AUTHORITY FLOW DATA IN MODAL THROUTH AJAX START --------- -->
<script>
function get_authority_flow(id,flow_id){ //alert('call get_authority_flow'); return false;
$('#ndata').empty();
$('#authority_user_data_view').empty();
$('#authority_data').empty();
$('#authority_flow_data').empty();

var spinner = $('#loader');
spinner.show();
$.ajax({
    type: "POST",
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
    url: "{{url('ajax/get-authority-approval-flow-data')}}",
    data: {id: id,flow_id:flow_id},
    success: function(xhr) {
        var html='';
        var html1='';
        if(xhr.status==200){
            const created_at_dt = xhr.flow.created_at.split("T"); 
            $('#authority_flow_name_view').text('➤ '+xhr.flow.flow_name);
            $('#authority_flow_created_at').text('➤ '+created_at_dt[0]);
           // $('#authority_flow_name_view').text('➤ '+xhr.flow.flow_name);
            var datas = xhr.datas;
            for (var i = 0; i < datas.length; i++) {
                html += '<tr><td>'+(i+1)+'</td>'+
                    '<td>'+datas[i].office_name+'</td>'+
                    '<td>'+datas[i].department_name+'</td>'+
                    '<td>'+datas[i].position_name+'</td>'+
                    '<td>'+datas[i].emp_type+' ➤ '+datas[i].name+'</td>'+
                '</tr>';
            }
            var authorities = xhr.authorities;
            for (var i = 0; i < authorities.length; i++) {
                html1 += '<tr><td>'+(i+1)+'</td>'+
                    '<td>'+authorities[i].office_name+'</td>'+
                    '<td>'+authorities[i].department_name+'</td>'+
                    '<td>'+authorities[i].position_name+'</td>'+
                    '<td>'+authorities[i].name+'</td>'+
                '</tr>';
            }
        }
        $('#authority_data').append(html);
        $('#authority_flow_data').append(html1);
        spinner.hide();
    }
});
}
</script>
<!-- ----GET AUTHORITY FLOW DATA IN MODAL THROUTH AJAX END ----------- -->
<script>
function get_flow(id){
$('#approval_flow_data_view').empty();
$('#authority_user_data_view').empty();
var spinner = $('#loader');
//spinner.show();
$.ajax({
    type: "POST",
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
    url: "{{url('ajax/get-leave-flow-data')}}",
    data: {id: id},
    success: function(xhr) {
        var html='';
        var html1='';
        var html2='';
        if(xhr.status==200){
            $('#flow_name_view').text('➤ '+xhr.flow.flow_name);
            var datas = xhr.datas;
            for (var i = 0; i < datas.length; i++) {
                html += '<tr><td>'+(i+1)+'</td>'+
                    '<td>'+datas[i].office_name+'</td>'+
                    '<td>'+datas[i].department_name+'</td>'+
                    '<td>'+datas[i].position_name+'</td>'+
                    '<td>'+datas[i].emp_type+' ➤ '+datas[i].name+'</td>'+
                '</tr>';
            }
            $('#approval_flow_data_view').append(html);
            var authorities = xhr.authorities;
            for (var i = 0; i < authorities.length; i++) {
                html1 += '<tr><td>'+(i+1)+'</td>'+
                    '<td>'+authorities[i].office_name+'</td>'+
                    '<td>'+authorities[i].department_name+'</td>'+
                    '<td>'+authorities[i].position_name+'</td>'+
                    '<td>'+authorities[i].name+'</td>'+
                '</tr>';
            }

        var notification = xhr.notification;
        
        for (var i = 0; i < notification.length; i++) {  
            html2 += '<tr><td>'+(i+1)+'</td>'+
                '<td>'+notification[i].email_for_approve+'</td>'+
                '<td>'+notification[i].email_for_reject+'</td>'+
                '<td>'+notification[i].sms_for_approve+'</td>'+
                '<td>'+notification[i].sms_for_reject+'</td>'+
                '<td>'+notification[i].app_for_approve+'</td>'+
                '<td>'+notification[i].app_for_reject+'</td>'+
            '</tr>';
        }
        }
        
        $('#authority_user_data_view').append(html1);
        $('#notification_data').append(html2);
        spinner.hide();
    }
});
}
</script>



<!-- ------START TO GET FLOW RECORDS VIEW--------- -->

<script>
function get_flow_data(id){
var spinner = $('#loader');
$('#flowdatalist').empty();
$.ajax({
    type: "POST",
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
    url: "{{url('ajax/get-flow-data')}}",
    data: {id: id},
    success: function(xhr) {
        var html='';
         if(xhr.status==200){
            var datas = xhr.data;
                 html += '<tr><td>#</td>'+
                    '<td>'+datas.flow_name+'</td>'+
                    '<td>'+datas.status+'</td>'+
                    '<td>'+dateFormate(datas.created_at)+'</td>'+
                '</tr>';
        }
        $('#flowdatalist').append(html);
        spinner.hide();
    }
});
}
</script>


<!-- ------------START EDIT FLOW DATA-------- -->
<script>
function edit_flow_data(id,name){  

$('#approval_flow_data_view').empty();
$('#authority_user_data_view').empty();
$('#notification_data').empty();

$('.flow-records').hide();
$('.hide_flow_name').show();
$('.leave-flow-data').show();
$('.leave-flow-records').show();
$('.approval-authority-view').show();
$('.setting-approval').show();

/*--------------------------*/
$('#flow_name_preview').text('➤ '+name);
 $('#flow_id').val(id);
$('.hide_flow_name').hide();

$('.settings_flow_list').show();
$('.setting-approval').show();

 /*-----------------------------*/
//var flow_id=id;
$.ajax({
    type: "POST",
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
    url: "{{url('ajax/get-leave-flow-data')}}",
    data: {id: id},
    success: function(xhr) { 
        var html='';
        var html1='';
        var html2='';
        if(xhr.status==200){
            $('#flow_name_view').text('➤ '+xhr.flow.flow_name);
            var datas = xhr.datas;
            for (var i = 0; i < datas.length; i++) { 
                html += '<tr><td>'+(i+1)+'</td>'+
                    '<td>'+datas[i].office_name+'</td>'+
                    '<td>'+datas[i].department_name+'</td>'+
                    '<td>'+datas[i].position_name+'</td>'+
                    '<td>'+datas[i].emp_type+' ➤ '+datas[i].name+'</td>'+
                    '<td>DT-2-<a class="text-primary" data-toggle="modal" data-target="#myModalLeave" onclick="get_leave_flow('+datas[i].id+')"><i class="fa fa-eye"></i></a>|<a href="{{url("delete-leave-flow")}}/'+datas[i].id+'" class="text-danger delete-button"><i class="fa fa-trash"></i></a></td>'+
                '</tr>';
            }
             $('#approval_flow_data_view').append(html);
            var authorities = xhr.authorities;
            for (var i = 0; i < authorities.length; i++) {  
                html1 += '<tr><td>'+(i+1)+'</td>'+
                    '<td>'+authorities[i].office_name+'</td>'+
                    '<td>'+authorities[i].department_name+'</td>'+
                    '<td>'+authorities[i].position_name+'</td>'+
                    '<td>'+authorities[i].name+'</td>'+
                    '<td>CCC<a class="text-primary" data-toggle="modal" data-target="#myModalAuthority" data-id="'+authorities[i].flow_id+'" onclick="get_authority_flow('+authorities[i].flow_id+','+authorities[i].id+')"><i class="fa fa-eye"></i></a>|<a href="{{url("delete-approval-authority")}}/'+authorities[i].id+'/'+authorities[i].flow_id+'" class="text-danger delete-button"><i class="fa fa-trash"></i></a></td>'+
                '</tr>';
            }

        var notification = xhr.notification;
        
        for (var i = 0; i < notification.length; i++) {  
            html2 += '<tr><td>'+(i+1)+'</td>'+
                '<td>'+notification[i].email_for_approve+'</td>'+
                '<td>'+notification[i].email_for_reject+'</td>'+
                '<td>'+notification[i].sms_for_approve+'</td>'+
                '<td>'+notification[i].sms_for_reject+'</td>'+
                '<td>'+notification[i].app_for_approve+'</td>'+
                '<td>'+notification[i].app_for_reject+'</td>'+
            '</tr>';
        }
        }
        
        $('#authority_user_data_view').append(html1);
        $('#notification_data').append(html2);
        spinner.hide();
    }
});


 

}
</script>


<!-- ------------END EDIT FLOW DATA-------- -->



<!-- ------END TO GET FLOW RECORDS VIEW--------- -->

<!-- CHANGE STATUS CODE---- -->
<script>
    $(document).on('click','.status_checks',function(){
        var status = ($(this).hasClass("btn-outline-success")) ? 'Inactive' : 'Active';
        var msg = (status=='Active')? 'Active' : 'Inactive';
        var current_element = $(this);
        //alert(current_element);
        swal({
            title: "Are you sure?",
            text: "Do you want to change status "+msg,
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, change status!",
            closeOnConfirm: false
        }, function (isConfirm) {
            if (isConfirm) {
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
                    url: "{{url('ajax/get-status-flow-data')}}",
                    type: "POST",
                    data: {id:$(current_element).attr('data'),status:status},
                    success: function(xhr){ //alert(data);
                        if(xhr.data.status=='Inactive'){
                            $('#inds'+xhr.data.id).addClass('btn-outline-danger');
                            $('#inds'+xhr.data.id).removeClass('btn-outline-success');
                            $('#inds'+xhr.data.id).text('Inactive');
                            // toastr.success("Inactive");
                        }else{
                            $('#inds'+xhr.data.id).addClass('btn-outline-success');
                            $('#inds'+xhr.data.id).removeClass('btn-outline-danger');
                            $('#inds'+xhr.data.id).text('Active');
                            // toastr.success("Active");
                        }
                         location.reload();
                        swal(xhr.data.status, "Succesfully "+xhr.data.status, "success");
                        
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        swal("Something Went to Wrong!", "Please try again", "error");
                    }
                });
            }
        });
    });
</script>

<script>
     $(document).ready(function() { 
     $('#example').DataTable( {  
       /* dom: 'Bfrtip',
        buttons: ['excel', 'pdf', 'print','copy','csv'] ,
        "bPaginate": false*/
    } );

     $('#abc').DataTable( {  
        dom: 'Bfrtip',
        buttons: ['excel', 'pdf', 'print','copy','csv'] ,
    } );

} );
</script>


@endsection('content')