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
                        <h5 class="">Edit Role</h5>
                    </div>
                    <div class="card-body">
                        <form class="forms-sample row" action="{{url('edit-user-role')}}/{{ $id }}" method="POST">
                            @csrf
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Role Name *</label>
                                    <input type="hidden" name="update_id" class="form-control" value="{{Request::segment(2)}}">
                                    <input type="text" class="form-control" id="office_name" name="office_name" value="@if(!empty($roles['name'] )){{$roles['name']}}@endif" placeholder="Enter Office Name" maxlength="50" required>
                                    <span id="letterNameError" style="color:red;font-size:13px"></span>
                                </div>
                            </div>
                            
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Role Description *</label>
                                    <textarea class="form-control" id="address" name="address" maxlength="200" rows="3" placeholder="Enter Address" required>@if(!empty($roles['description'])){{$roles['description']}}@endif</textarea>
                                </div>
                            </div>
                            <div class="col-sm-1">
                                <button type="submit" class="btn btn-primary btn-sm mr-2">Submit</button>
                            </div>
                            @if(!empty($roles))
                            <div class="col-sm-1">
                                <a href="{{url('user-role')}}" class="btn btn-primary btn-sm">Back</a>
                            </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>

       
    </div>

 
  

@endsection('content')