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
                        </div>
                    </div>
					<?php // echo "<pre>";print_r($short_leave_count);?>
                    <div class="card-body">
                        <table id="examples" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>User Id</th>
                                    <th>User Name</th>
                                    <th>Office Id</th>
                                    <th>Leave Type Id</th>
                                    <th>Employee Type Id</th>
                                    <th>Leave Name</th>
                                    <th>Total Short Leaves</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($short_leave_count) > 0)
									@foreach($short_leave_count as $data_rec)
										
										<tr>
										
											<td>{{$data_rec->id}}</td> 
											<td>{{$data_rec->user_id}}</td>
											<td>{{$data_rec->user_name}}</td>
										   
											<td>{{$data_rec->office_id}}</td>
											<td>{{$data_rec->leave_type}}</td>
											<td>{{$data_rec->emp_type}}</td>
											<td>{{$data_rec->leave_name}}</td>
											<td>{{$data_rec->total_leave}} </td>                                    
											<td> 
												@if($data_rec->total_leave > 3)
													<button type="button" data-organization_id="{{$data_rec->organisation_id}}" data-user_id="{{$data_rec->user_id}}" data-Office_id="{{$data_rec->office_id}}" class="btn btn-success deduct-short-leave">Deduct 1 Casual Leave </button>
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


           
        </div>
    </div>

     
   
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    

<script>
  $(document).ready(function () {
      var datatable = $('#examples').dataTable();
	  $( '.dt-buttons' ).remove();
  });
	
	
	$(document).on('click','.deduct-short-leave',function(){
           
		swal({
			title: "Are you sure?",
			text: "Do you want to Deduct 1 Casual leave ??",
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: "Yes",
			closeOnConfirm: false
		}, function (isConfirm) {
			if (isConfirm) {
				var spinner = $('#loader');
				spinner.show();	
				let user_id = $('.deduct-short-leave').data('user_id');			
				let office_id = $('.deduct-short-leave').data('office_id');				
				let organization_id = $('.deduct-short-leave').data('organization_id');				
				
				$.ajax({
					headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
					url : "{{url('ajax/deduct-casual-leave')}}",
					type: "POST",
					data: {user_id:user_id, office_id:office_id, organization_id:organization_id},
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