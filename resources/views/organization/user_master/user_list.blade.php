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
            <div class="col-12 stretch-card">
                <div class="card">
                    <div class="card-header card-height">
                        <div class="row">
                            <div class="col-md-12 col-12">
                                <h5 class="" id="getCameraSerialNumbers">User List</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="example" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>email</th>
									<th>Company Name</th>
									<th>Role</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

 
  
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function () {
        var datatable = $('#example').dataTable({
            ajax: "{{url('ajax/get-user-list')}}",
            columns: [
                {data:'user_id',
                    render: function (data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                {data:'user_name'},
                {data:'user_email'},
              
			    {data:'company_name'},
			    {data:'roles_name'},
              
                {data: null,
                    mRender:function ( data, type, row ) {
                        return '<a href="{{url("edit-user-list")}}/'+data.user_id+'" class="text-primary mx-2"><i class="fa fa-edit"></i></a>'+
                        '<a href="{{url("delete-user")}}/'+data.user_id+'" class="text-danger delete-button"><i class="fa fa-trash"></i></a>';
                    }
                },
            ],dom: 'Bfrtip',buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
        });
    });
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
<script>
    $(document).on('click','.status_checks',function(){
        var status = ($(this).hasClass("btn-outline-success")) ? 'Inactive' : 'Active';
        var msg = (status=='Active')? 'Active' : 'Inactive';
        var current_element = $(this);
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
                    url: "{{url('ajax/get-status-office')}}",
                    type: "POST",
                    data: {id:$(current_element).attr('data'),status:status},
                    success: function(xhr){
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
    function get_country_id(id) {
        $('#state_id').empty();
        $('#city_id').empty();
        $.ajax({
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
            },
            url: "{{url('ajax/get-state')}}",
            data: {
                country_id: id
            },
            success: function(xhr) {
                var datas = xhr.data;
                $('#state_id').append('<option value="">--Select--</option>');
                for (var i = 0; i < datas.length; i++) {
                    $('#state_id').append('<option value="' + datas[i].id + '">' + datas[i].name +
                        '</option>');
                }
            }
        });
    }
</script>

<script>
    function get_state_id(id) {
        $('#city_id').empty();
        $.ajax({
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
            },
            url: "{{url('ajax/get-city')}}",
            data: {
                state_id: id
            },
            success: function(xhr) {
                var datas = xhr.data;
                $('#city_id').append('<option value="">--Select--</option>');
                for (var i = 0; i < datas.length; i++) {
                    $('#city_id').append('<option value="' + datas[i].id + '">' + datas[i].name +
                        '</option>');
                }
            }
        });
    }
</script>

<script type="text/javascript">
    $(function () {
        $("#source_name").keypress(function (e) {
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

    $(document).ready(function() {
        $('.country_name').select2();
        $('.state_name').select2();
        $('.city_name').select2();
    });
</script>
@endsection('content')