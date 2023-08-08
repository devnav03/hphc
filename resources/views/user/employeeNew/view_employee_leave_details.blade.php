@extends('layouts.organization.app')
@section('content')
<style>
    .lable-primary{
        background-color: #337ab7;
        color: #fff;
        padding: 0.2em 0.6em 0.3em;
        border-radius: 0.8em;
        font-size: 14px;
        white-space: nowrap;
    }
    .lable-success{
        background-color: #5cb85c;
        color: #fff;
        padding: 0.2em 0.6em 0.3em;
        border-radius: 0.8em;
        font-size: 14px;
        white-space: nowrap;
    }
    a:hover {
        color: #fff;
        text-decoration: none;
    }
    #leave_data td{
        border: 1px solid #80808036 !important;
    }
    .tbl-border th{
        border: 1px solid #80808036 !important;
    }
</style>
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header">
                        <h5 class="">Search Employee Leave</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" class="row">
                            @csrf
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Office *</label>
                                    <select class="form-control" id="office_id" name="office_id" required onchange="get_office_id(this.value);">
                                        @if(!empty($office))
                                            <option value="">--Select--</option>
                                            @foreach($office as $row)
                                                <option value="{{$row->id}}" @if(!empty($update->office_id)) @if($update->office_id==$row->id) selected @endif @endif>{{$row->office_name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Department Name</label>
                                    <select class="form-control" id="department_id" name="department_id" onchange="get_department_id(this.value);">
                                        <option value="">--Select--</option>
                                        @if(!empty($department))
                                            @foreach($department as $depa)
                                                <option value="{{$depa->id}}" @if(!empty($update->department_id)) @if($update->department_id==$depa->id) selected @endif @endif>{{$depa->department_name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <span id="letterNameError" style="color:red;font-size:13px"></span>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Employee Name</label>
                                    <select class="form-control" id="user_id" name="user_id">
                                        <option value="">--Select--</option>
                                            @if(!empty($leave_details))
                                                @foreach($leave_details as $row)
                                                    <option value="{{$row->id}}">{{$row->name}}</option>
                                                @endforeach
                                            @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Month *</label>
                                    <select class="form-control" id="month" name="month" required>
                                            <option value="">--Select--</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Year *</label>
                                    <select class="form-control" id="year" name="year" required>
                                        <option value="">--Select--</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Status</label>
                                    <select class="form-control" id="status" name="status">
                                        <option value="">--Select--</option>
                                        <option value="Pending">Pending</option>
                                        <option value="Approved">Approved</option>
                                        <option value="Reject">Reject</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-primary btn-sm mr-2">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">


         <div class="col-12 grid-margin stretch-card" id="default_result">
                <div class="card">
                    <div class="card-header card-height">
                        <div class="row">
                            <div class="col-md-12 col-12">
                                <h5 class="" id="getCameraSerialNumbers">Employee Leave List</h5>
							</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="examples" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Employee Name</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Duration</th>
                                    <th>Leave Type</th>
                                    <th>Short Leave Time</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($data))
                                @foreach($data as $data_rec)
                                <tr>
								
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$data_rec->name}}</td>
                                    <td>{{$data_rec->start_date}}
									<input type="hidden" id="start_date_{{$data_rec->id}}" value="{{$data_rec->start_date}}"/>
									<input type="hidden" id="user_id_{{$data_rec->id}}" value="{{$data_rec->user_id}}"/> 
										
									</td>
                                    <td>
										<?php 
										$search_tour_leave = 'TOUR';

										if(strpos(strtoupper($data_rec->leave_type), $search_tour_leave) != false){ ?>
											<input type="date" min="{{$data_rec->start_date}}" name="end_date_{{$data_rec->id}}" data-leave_id="{{$data_rec->id}}" class="end_date" id="end_date_{{$data_rec->id}}" value="{{$data_rec->end_date}}"> <?php 
										}else{ ?>
											{{$data_rec->end_date}} <?php 
										} ?>
										
									</td>
                                    <td id="duration">{{$data_rec->duration}} Days</td>
                                    <td>{{$data_rec->leave_type}}</td>
                                    <td>@if($data_rec->time_duration==null){{'NA'}} @else {{$data_rec->time_duration}} @endif</td>
                                    <td>
                                        @if($data_rec->status=='Pending')
                                        <a href data-toggle="modal" data-target="#myModal" onclick="checkAuthorityLeave('{{$data_rec->id}}')" class="btn-xs btn btn-primary inds'{{$data_rec->id}}'"> Pending</a>

                                        @elseif($data_rec->status=='Approved')
                                         <a href data-toggle="modal" data-target="#myModal" onclick="show_data('{{$data_rec->id}}')" class="btn-xs btn btn-success inds'{{$data_rec->id}}'"> Approved</a>
                                         @elseif($data_rec->status=='Reject')
                                         <a href data-toggle="modal" data-target="#myModal" onclick="show_data('{{$data_rec->id}}')" class="btn-xs btn btn-danger inds'{{$data_rec->id}}'"> Rejected</a>
                                         @endif

                                    </td>
                                </tr>
                                @endforeach
                               @endif 
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>   




            <div class="col-12 grid-margin stretch-card" id="search_result" style="display:none;">
                <div class="card">
                    <div class="card-header card-height">
                        <div class="row">
                            <div class="col-md-12 col-12">
                                <h5 class="" id="getCameraSerialNumbers">Employee Leave Results</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="example" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Employee Code</th>
                                    <th>Employee Name</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Duration</th>
                                    <th>Leave Type</th>
                                    <th>Short Leave Time</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Leave Request</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
                <div class="modal-body">
                    <div id="leave_reason"></div>
                    <table class="table tbl-border">
                        <thead>
                            <tr>
                                <th scope="col">Emp Code</th>
                                <th scope="col">Name</th>
                                <th scope="col">Leave Type</th>
                                <th scope="col">Start Data</th>
                                <th scope="col">End Data</th>
                                <th scope="col">Duration</th>
                                <th scope="col">Short Leave Duration</th>
                                <th scope="col">Reason</th>
                                <th scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody id="leave_data">
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer" id="leave_id"></div>
            </div>
        </div>
    </div>
	
	
	<!------------------------- Show forward modal By ankit ------------------------>
	
	<div id="showForwardModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Forward Request</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
                <div class="modal-body">
                    <div id="leave_reason"></div>
                    <table class="table tbl-border">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Mobile</th>
                            </tr>
                        </thead>
                        <tbody id="forward_user_data">
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer" id="forward_button"></div>
            </div>
        </div>
    </div>
    <script>
      $(function () {
        $('form').on('submit', function (e) {
        var spinner = $('#loader');
        spinner.show();
          e.preventDefault();
            $('#example').dataTable().fnClearTable();
            $('#example').dataTable().fnDraw();
            $('#example').dataTable().fnDestroy();
            var datatable = $('#example').dataTable({
                "ajax": function (data, callback, settings) {
                    $.ajax({
                    url: "{{url('ajax/get-employee-leave-data')}}",
                    dataType:"json",
                    type: 'POST',
                    data: $('form').serialize(),
                        success: function(data) {
                            callback(data);
                            spinner.hide();
                            $('#default_result').hide();
                            $('#search_result').show()
                        }
                    });
                },
                columns: [
                    {data:'id',
                        render: function (data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {data:'employee_code'},
                    {data:'name'},
                    {data: null,
                        mRender:function ( data, type, row ) {
                            return dateFormate(data.start_date);
                        }
                    },
                    {data: null,
                        mRender:function ( data, type, row ) {
                            return dateFormate(data.end_date);
                        }
                    },
                    {data:'duration'},
                    {data:'leave_type'},
                    {data:null,
                        mRender:function ( data, type, row ) {
                            if(data.time_duration==null){
                                return 'NA';
                            } else{
                                return data.time_duration;
                          
                        }
                     }   
                    },
                    {data: null,
                        mRender:function ( data, type, row ) {
                            if(data.status=='Pending'){
                                return '<a href data-toggle="modal" data-target="#myModal" onclick="checkAuthorityLeave('+data.id+')" class="btn-xs btn btn-primary inds'+data.id+'"> Pending</a>';
                            }else if(data.status=='Approved'){
                                return '<a href data-toggle="modal" data-target="#myModal" onclick="show_data('+data.id+')" class="btn-xs btn btn-success inds'+data.id+'">Approved</a>';
                            }else if(data.status=='Reject'){
                                return '<a href data-toggle="modal" data-target="#myModal" onclick="show_data('+data.id+')" class="btn-xs btn btn-danger inds'+data.id+'">Rejected</a>';
                            }
                        }
                    }
                ]
            },
			{
				order: [[ 0, 'desc' ]]
			}
			
			);
        });
		});
		
		function checkAuthorityLeave(id){
			
			var spinner = $('#loader');
            spinner.show();
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
                type: 'POST',
                url : "{{url('ajax/apply-leaves-by-authority')}}", 
                data: {id:id},
                success:function(xhr){
					spinner.hide();
					if(xhr.data.result){
						alert(xhr.data.message);
						window.location.href = 'add-approval-flow/'+xhr.data.flow_id;
						$("#myModal").modal('hide');						
						return false;
					}else{
						show_data(id)
					}                  
                }
            });
		}
    
	
        function show_data(id){
			
			////// call this function to check user is in leave authority or not			
			//checkAuthorityLeave(id);
			
            var spinner = $('#loader');
            spinner.show();
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
                type: 'POST',
                url : "{{url('ajax/view-emp-leave-leave-data')}}", 
                data: {id:id},
                success:function(xhr){
                    if(xhr.status==200){  
						if(xhr.data.time_duration==null) { time_duration='NA';} else{ time_duration=xhr.data.time_duration;}
                        var html='<tr>'+
                        '<td>'+xhr.data.employee_code+'</td>'+
                        '<td>'+xhr.data.name+'</td>'+
                        '<td>'+xhr.data.leave_name+'</td>'+
                        '<td>'+dateFormate(xhr.data.start_date)+'</td>'+
                        '<td>'+dateFormate(xhr.data.end_date)+'</td>'+
                        '<td>'+xhr.data.duration+' Days</td>'+
                        '<td>'+time_duration+'</td>'+
                        '<td>'+xhr.data.reason_for_leav_comp+'</td>'+
                        '<td>'+xhr.data.approver_name+'</td>'+
                        '</tr>';
                        $('#leave_data').html(html);
						
						var status_string = "";
						
						////////////////////// Check approval authority is greater than 1 then show forward button //////////////////////
						if(xhr.approval_count > 1 && xhr.data.status=='Pending' && xhr.forward_status== 0){
							status_string = '<button data-flow_id="'+xhr.data.flow_id+'" data-leave_id="'+xhr.data.id+'" data-office_id="'+xhr.data.office_id+'" class="btn btn-info btn-sm showForwardModal inds'+xhr.data.id+'" class="close" data-dismiss="modal">Forward</button>';
						}
						
						if(xhr.withdraw_status){
							status_string += '<button data-leave_id="'+xhr.data.id+'" class="btn btn-info btn-sm withdrawLeaves inds'+xhr.data.id+'" class="close" data-dismiss="modal">Withdraw</button>';
						}					
 
                        if(xhr.data.status=='Pending'){                           
							status_string += '<button data="'+xhr.data.id+'"class="status_checks btn btn-success hides btn-sm inds'+xhr.data.id+'">Approve</button>'+
                            '<button data="'+xhr.data.id+'" class="status_checks btn btn-danger btn-sm inds'+xhr.data.id+'" class="close" data-dismiss="modal">Reject</button>'+
                            '<span class="btn btn-info btn-sm" data-dismiss="modal">skip</span>';							
                        }
						
						if(xhr.data.status=='Approved'){
							status_string += '<button data="'+xhr.data.id+'" class="status_checks btn btn-danger btn-sm inds'+xhr.data.id+'" class="close" data-dismiss="modal">Reject</button>'+
                            '<span class="btn btn-info btn-sm" data-dismiss="modal">skip</span>';
                           
                        }if(xhr.data.status=='Reject'){
							status_string += '<button data="'+xhr.data.id+'" class="status_checks btn btn-success btn-sm inds'+xhr.data.id+'" class="close" data-dismiss="modal">Approve</button>'+
                            '<span class="btn btn-info btn-sm" data-dismiss="modal">skip</span>';
                            
                        }
						$('#leave_id').html(status_string);
						
                        /* if(xhr.data.status=='Pending'){
                            $('#leave_id').html('<button data="'+xhr.data.id+'"class="status_checks btn btn-success hides btn-sm inds'+xhr.data.id+'">Approve</button>'+
                            '<button data="'+xhr.data.id+'" class="status_checks btn btn-danger btn-sm inds'+xhr.data.id+'" class="close" data-dismiss="modal">Reject</button>'+
                            '<span class="btn btn-info btn-sm" data-dismiss="modal">skip</span>');
                        }if(xhr.data.status=='Approved'){
                            $('#leave_id').html('<button data="'+xhr.data.id+'" class="status_checks btn btn-danger btn-sm inds'+xhr.data.id+'" class="close" data-dismiss="modal">Reject</button>'+
                            '<span class="btn btn-info btn-sm" data-dismiss="modal">skip</span>');
                        }if(xhr.data.status=='Reject'){
                            $('#leave_id').html('<button data="'+xhr.data.id+'" class="status_checks btn btn-success btn-sm inds'+xhr.data.id+'" class="close" data-dismiss="modal">Approve</button>'+
                            '<span class="btn btn-info btn-sm" data-dismiss="modal">skip</span>');
                        } */
                        spinner.hide();
                    }
                }
            });
        }
        function get_office_id(id) {
            var spinner = $('#loader');
            spinner.show();
            $('#department_id').empty();
            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
                },
                url: "{{url('ajax/get-department-name')}}",
                data: {
                    department_id: id
                },
                success: function(xhr) {
                    var datas = xhr.data;
                    $('#department_id').append('<option value="">--Select--</option>');
                    for (var i = 0; i < datas.length; i++) {
                        $('#department_id').append('<option value="'+datas[i].id+'">'+datas[i].department_name+'</option>');
                    }
                    spinner.hide();
                }
            });
        }
        function get_department_id(id) {
            var spinner = $('#loader');
            spinner.show();
            $('#user_id').empty();
            $('#leave_type').empty();
            var office_id = $('#office_id').val();
            $.ajax({
                type: "POST",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
                url: "{{url('ajax/get-employee-against-department')}}",
                data: {
                    office_id: office_id,
                    department_id: id
                },
                success: function(xhr) {
                    var datas = xhr.data;
                    $('#user_id').append('<option value="">--Select--</option>');
                    for (var i = 0; i < datas.length; i++) {
                        $('#user_id').append('<option value="'+datas[i].id+'">'+datas[i].name+' ( '+datas[i].employee_code+' )</option>');
                    }
                    spinner.hide();
                }
            });
        }
        function get_user_id(id){
            var spinner = $('#loader');
            spinner.show();
            $('#leave_type').empty();
            var office_id = $('#office_id').val();
            var department_id = $('#department_id').val();
            $.ajax({
                type: "POST",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
                url: "{{url('ajax/get-leave-type')}}",
                data: {
                    office_id: office_id,
                    department_id: department_id,
                    user_id: id
                },
                success: function(xhr) {
                    var datas = xhr.data;
                    $('#leave_type').append('<option value="">--Select--</option>');
                    for (var i = 0; i < datas.length; i++) {
                        $('#leave_type').append('<option value="'+datas[i].id+'">'+datas[i].name+' ( '+datas[i].totalleave+' )</option>');
                    }
                    spinner.hide();
                }
            });
        }
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <script>
        $(document).on('click','.status_checks',function(){
            var status = ($(this).hasClass("btn-success")) ? 'Approved' : 'Reject';
            var msg = (status=='Approved')? 'Approved' : 'Reject';
            var current_element = $(this);
            swal({
                title: "Are you sure?",
                text: "Do you want to change status "+msg,
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes",
                closeOnConfirm: false
            }, function (isConfirm) {
                if (isConfirm) {
                    var spinner = $('#loader');
                    spinner.show();
                    $.ajax({
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
                        url: "{{url('employee-leave-status')}}",
                        type: "POST",
                        data: {id:$(current_element).attr('data'),status:status},
                        success: function(xhr){
                            if(xhr.data.status=='Reject'){
                                $('.inds'+xhr.data.id).addClass('btn-danger');
                                $('.inds'+xhr.data.id).removeClass('btn-success');
                                $('.inds'+xhr.data.id).text('Rejected');
                            }else{
                                $('.inds'+xhr.data.id).addClass('btn-success');
                                $('.inds'+xhr.data.id).removeClass('btn-danger');
                                $('.inds'+xhr.data.id).text('Approved');
                            }
                            $('.hides').hide();
                            spinner.hide();
                            swal(xhr.data.status, "Succesfully "+xhr.data.status, "success");
							setTimeout(function(){ window.location.reload(); }, 500); 
                            
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            spinner.hide();
                            swal("Error deleting!", "Please try again", "error");
                        }
                    });
                }
            });
        });
    </script>

<script>
  $(document).ready(function () {
      var datatable = $('#examples').dataTable({
      dom: 'Bfrtip',
      buttons: [
      'copy', 'csv', 'excel', 'pdf', 'print'
      ],
      });
  });
  
  $(document).on('click','.showForwardModal',function(){
		var spinner = $('#loader');
		spinner.show();
		$("#showForwardModal").modal();
		let flow_id = $('.showForwardModal').data('flow_id');
		let leave_id = $('.showForwardModal').data('leave_id');
		let office_id = $('.showForwardModal').data('office_id');
		$.ajax({
			headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
			type: 'POST',
			url : "{{url('ajax/view-forward-user-data')}}", 
			data: {flow_id:flow_id, office_id:office_id, leave_id:leave_id},
			success:function(xhr){
				if(xhr.status==200){  if(xhr.data.time_duration==null) { time_duration='NA';} else{ time_duration=xhr.data.time_duration;}
					var html= xhr.data;
					$('#forward_user_data').html(html);
					
					var status_string = '<button data-leave_id="'+leave_id+'" class="forward_to_user btn btn-success hides btn-sm inds">Forward</button>'+
						'<span class="btn btn-info btn-sm" data-dismiss="modal">skip</span>';
					
					$('#forward_button').html(status_string);
					spinner.hide();
				}
			}
		});
	});
	
	
	$(document).on('click','.forward_to_user',function(){
		var spinner = $('#loader');
		spinner.show();
		
		
		let leave_id = $(this).data('leave_id');		

		var user_ids = $("input[name='leave_approve_req_forward[]']:checked").map(function () {
			return this.value;
		}).get();
		
		if(user_ids ==""){
			spinner.hide();
			alert("Please select user");
			return false;
		}
		$("#showForwardModal").modal('hide');
	
		$.ajax({
			headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
			type: 'POST',
			url : "{{url('ajax/send-forward-request')}}", 
			data: {user_ids:user_ids, leave_id:leave_id},
			success:function(xhr){			
					
				spinner.hide();
				alert(xhr.message);
			}
		});
	});
	
	$(document).on('click','.withdrawLeaves',function(){
           
		swal({
			title: "Are you sure?",
			text: "Do you want to withdraw your leaves",
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Yes",
			closeOnConfirm: false
		}, function (isConfirm) {
			if (isConfirm) {
				var spinner = $('#loader');
				spinner.show();					
				
				let leave_id = $('.withdrawLeaves').data('leave_id');
				
				$.ajax({
					headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
					url: "{{url('ajax/withdraw-employee-leaves')}}",
					type: "POST",
					data: {leave_id:leave_id},
					success: function(xhr){
						
						$('.hides').hide();
						spinner.hide();
						swal(xhr.message, "Succesfully "+xhr.message, "success");
						
						setTimeout(function(){ window.location.reload(); }, 500); 
						
					},
					error: function (xhr, ajaxOptions, thrownError) {
						spinner.hide();
						swal("Error withdraw!", "Please try again", "error");
					}
				});
			}
		});
    });
	
	$(".end_date").on("click", function(){
		let leave_id = $(this).data('leave_id');		
        var start = $("#start_date_"+leave_id).val();
		$("#end_date_"+leave_id).attr("min", start);
	});
	
    $(".end_date").on("change", function(){
		
        let leave_id = $(this).data('leave_id');
		var start = $("#start_date_"+leave_id).val();
        var end_date = $("#end_date_"+leave_id).val();
		let leave_user_id = $("#user_id_"+leave_id).val();

        var startDay = new Date(start);
        var endDay = new Date(end_date);
        var millisecondsPerDay = 1000 * 60 * 60 * 24;

        var millisBetween = endDay.getTime() - startDay.getTime();
        var days = millisBetween / millisecondsPerDay;
		let duration = Math.floor(days);
        $("#duration").html(Math.floor(days) + " Days");		
		
		updateTourLeaveEndDate(leave_id, end_date, duration, leave_user_id);
    });
	
	function updateTourLeaveEndDate(leave_id, end_date, duration, leave_user_id){
		var spinner = $('#loader');
		$.ajax({
			headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
			url: "{{url('ajax/update-tour-leave-end-date')}}",
			type: "POST",
			data: {leave_id:leave_id, end_date:end_date, duration:duration, leave_user_id:leave_user_id},
			success: function(xhr){
				
				$('.hides').hide();
				spinner.hide();
				
				swal({
					title: xhr.type,
					text: xhr.message,
					type: xhr.type,
					//showCancelButton: true,
					confirmButtonColor: "#DD6B55",
					confirmButtonText: "Ok",
					closeOnConfirm: false
				})
				
			},
			error: function (xhr, ajaxOptions, thrownError) {
				spinner.hide();
				swal("Error !", "Please try again", "error");
				swal({
					title: "Error !",
					text: xhr.message,
					type: 'error',
					//showCancelButton: true,
					confirmButtonColor: "#DD6B55",
					confirmButtonText: "Ok",
					closeOnConfirm: false
				})
			}
		});
	}
   
</script>


@endsection('content')