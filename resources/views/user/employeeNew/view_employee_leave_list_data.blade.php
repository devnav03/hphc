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


         <div class="col-12 grid-margin stretch-card" id="default_result">
                <div class="card">
                    <div class="card-header card-height">
                        <div class="row">
                            <div class="col-md-6 col-7">
                                <h5 class="" id="getCameraSerialNumbers">Employee Remaining Leaves List</h5>
								
                            </div>
							
							<div class="col-md-6 col-5">
                               
								<button type="button" class=" btn-xs btn btn-success assignYearlyLeaveToEmployee">Assign Yearly Leaves </button>
                            </div>
                        </div>
                    </div>
					<?php //echo "<pre>";print_r($leave_details);?>
                    <div class="card-body">
                        <table id="examples" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>User Id</th>
                                    <th>Leave Id</th>
                                    <th>Employee Name</th>
                                    <th>Employee Type</th>
                                    <th>Leave Type</th>
                                    <th>Total Leaves</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($leave_details))
                                @foreach($leave_details as $data_rec)
                                <tr>
								
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$data_rec->user_id}}</td>
                                    <td>{{$data_rec->leave_type_id}}</td>
                                    <td>{{$data_rec->name}}</td>
                                    <td>{{$data_rec->emp_type}}</td>
                                    <td>{{$data_rec->leave_name}}</td>
                                    <td>{{$data_rec->total_leaves}} Days</td>                                    
                                </tr>
                                @endforeach
                               @endif 
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>   


           
        </div>
    </div>

     
   
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    

<script>
  $(document).ready(function () {
      var datatable = $('#examples').dataTable();
	  $( '.dt-buttons' ).remove();
  });
	
	
	$(document).on('click','.assignYearlyLeaveToEmployee',function(){
           
		swal({
			title: "Are you sure?",
			text: "Do you want to Assign leaves to all emplyee ??",
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
					url : "{{url('ajax/assign-leaves')}}",
					type: "POST",
					data: {},
					success: function(xhr){						
						$('.hides').hide();
						spinner.hide();
						swal("Success", xhr.message, "success");					
						
					},
					error: function (xhr, ajaxOptions, thrownError) {
						spinner.hide();
						swal("Error !", "Please try again", "error");
					}
				});
			}
		});
    });
   
</script>


@endsection('content')