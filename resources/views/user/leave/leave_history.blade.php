@extends('layouts.user.app')
@section('content')
<style>
    .label-info{
        background-color: #5cb85c;
    }
    .label-danger{
        background-color: red;
    }
</style>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.25/webcam.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header card-height">
                        <div class="row">
                            <div class="col-md-10 col-6">
                                <h5 class="">Leave Histroy</h5>
                            </div>
                            
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="example" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Duration</th>
                                    <th>Leave Type</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">View Leave Reason</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
                <div class="modal-body" id="reason_for_leav_comp_desc">
                    
                </div>
                <div class="modal-footer">
                    <span class="btn btn-danger btn-sm" data-dismiss="modal">Close</span>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            var datatable = $('#example').dataTable({
                ajax: "{{url('ajax/employee-leaves')}}",
                columns: [
                    {data:'id',
                        render: function (data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
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
                    {data: null,
                        mRender:function ( data, type, row ) {
                            return '<span>'+data.duration+' days</span>';
                        }
                    },
                    {data:'name'},
                    {data: null,
                        mRender:function ( data, type, row ) {
                            if(data.status=='Reject'){
                                return '<span class="label label-danger">'+data.status+'</span>';
                            }else if(data.status=='Approved'){
								
								let buttons = '<span class="label label-info">'+data.status+'</span>';
								if(data.withdraw_button){
									buttons += ' <span class="label label-primary withdrawLeaves" data-leave_id="'+data.id+'">Withdraw</span>' ;
								}								 
								return buttons;
								
                            }else{
                                return '<span class="label label-primary">'+data.status+'</span>';
                            }
                        }
                    },
                    {data: null,
                        mRender:function ( data, type, row ) {
                            return '<a data-toggle="modal" data-target="#myModal" onclick="show_reason('+data.id+')" class="text-primary mx-2"><i class="fa fa-eye"></i></a>';
                        }
                    },
                ]
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
		
        function show_reason(id){
            var spinner = $('#loader');
            spinner.show();
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
                type: 'POST',
                url : "{{url('ajax/get-leave-reason')}}", 
                data: {id:id},
                success:function(xhr){
                    if(xhr.status==200){
                        $('#reason_for_leav_comp_desc').html(xhr.data.reason_for_leav_comp);
                        spinner.hide();
                    }
                }
            });
        }
    </script>
    @endsection('content')