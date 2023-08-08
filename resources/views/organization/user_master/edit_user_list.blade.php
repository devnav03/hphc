@extends('layouts.organization.app')
@section('content')
<style>
    .select2-container .select2-selection--single {
	height: 2.2rem !important;
    }
    .select2-container--default .select2-selection--single {
	background-color: #fff;
	border: 1px solid #aaaaaa73 !important;
	border-radius: 0px !important;
    }
    .select2-container{
	width:100% !important;
    }
</style>
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
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />


<div class="main-panel"> 
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header">
                        <h5 class="">Edit User</h5>
					</div>
                    <div class="card-body">
                        <form class="forms-sample row" action="{{url('edit-user-list')}}/{{ $id }}" method="POST">
                            @csrf
                           
						   <?php //echo "<pre>"; print_r($role->toArray()); echo "</pre>"; die;
							 $role_new =  $role->toArray();
							 if(isset($role_ids['role_id'])){
								 $check_role_id = $role_ids['role_id'];
								 }else{
								 $check_role_id = 0;
								 }
							   ?>
						   
							<div class="col-sm-4">
                                <div class="form-group">
                                    <label>Select Role *</label>
									<input type="hidden" name="update_id" class="form-control" value="{{Request::segment(2)}}">
									
                                    <select class="form-control country_name" name="role_id" required>
                                        @if(!empty($role_new))
										<option value="">--Select--</option>
										@foreach($role_new as $roles)
										<option value="{{$roles['id']}}" @if($roles['id']==$check_role_id) selected @endif>{{$roles['name']}}</option>
										@endforeach
                                        @endif
									</select>
								</div>
							</div>
							<div class="col-sm-8">
							</div>
							<br>
							 <div class="col-sm-1">
                                <button type="submit" class="btn btn-primary btn-sm mr-2">Submit</button>
                            </div>
							
						</form>
					</div>
				</div>
			</div>
		</div>
	
	
@endsection('content')	