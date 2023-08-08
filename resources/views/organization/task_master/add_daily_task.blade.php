@extends('layouts.organization.app')
@section('content')
<style>
    .select2-container .select2-selection--multiple {
        height: 2.2rem !important;
    }
    .select2-container--default .select2-selection--multiple {
        background-color: #fff;
        border: 1px solid #aaaaaa73 !important;
        border-radius: 0px !important;
    }
    .select2-container{
        width:100% !important;
    }
    .select2-container .select2-selection--single {
        height: 2.2rem !important;
    }
    .select2-container--default .select2-selection--single {
        background-color: #fff;
        border: 1px solid #aaaaaa73 !important;
        border-radius: 0px !important;
    }
</style>
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-header">
                        <h5 class="">Add Daily Task</h5>
                    </div>
                    <div class="card-body">
                    <form class="forms-sample row" action="{{url('add-daily-task')}}" method="POST">
                            @csrf
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Employee *</label>
                                    <select class="form-control" id="user_id" name="user_id" required>
                                        @if(!empty($user_name))
                                            <option value="">--Select--</option>
                                            @foreach($user_name as $row2)
                                                <option value="{{$row2->id}}" data-id="{{$row2->id}}" @if(!empty($update->user_id)) @if($update->user_id==$row2->id) selected @endif @endif>{{$row2->name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Task *</label>
                                    <input type="hidden" name="update_id" class="form-control" value="{{Request::segment(2)}}">
                                    <select class="form-control" id="project_id" name="project_id" onchange="get_project_id(this.value);" required>
                                        @if(!empty($task_name))
                                            <option value="">--Select--</option>
                                            @foreach($task_name as $row)
                                                <option value="{{$row->id}}" data-id="{{$row->id}}" @if(!empty($update->project_id)) @if($update->project_id==$row->id) selected @endif @endif>{{$row->project_name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Task Priority *</label>
                                    <select class="form-control" id="task_priority" name="task_priority" required>
                                        <option value="">--Select--</option>
                                        <option value="High" @if(!empty($update->task_priority)) @if($update->task_priority=="High") selected @endif @endif>High</option>
                                        <option value="Middle" @if(!empty($update->task_priority)) @if($update->task_priority=="Middle") selected @endif @endif>Middle</option>
                                        <option value="Low" @if(!empty($update->task_priority)) @if($update->task_priority=="Low") selected @endif @endif>Low</option>
                                    </select>
                                </div>
                            </div>
                            <!-- <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Estimation Duration *</label>
                                    <select class="form-control" id="activity_id" name="activity_id[]" multiple="multiple" required>
                                        @if(!empty($activity_name))
                                            <option value="">--Select--</option>
                                            @foreach($activity_name as $row1)
                                                <option value="{{$row1->id}}" data-id="{{$row1->id}}" @if(!empty($update->activity_id)) @if($update->activity_id==$row1->id) selected @endif @endif>{{$row1->activity_name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div> -->
                            
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Attachment *</label>
                                    <input type="file" class="form-control" id="attachment" name="attachment" required>
                                </div>
                            </div>
                            
                            
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Description *</label>
                                    <textarea id="description" name="description" class="form-control" rows="4" cols="50">@if(!empty($update->description)){{$update->description}}@endif</textarea>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-primary btn-sm mr-2">Assign</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- <div class="row">
            <div class="col-12 stretch-card">
                <div class="card">
                    <div class="card-header card-height">
                        <div class="row">
                            <div class="col-md-12 col-12">
                                <h5 class="" id="">Assign Task List</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="example" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Task Name</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Activity</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div> -->

    </div>

    



    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- <script>
    function get_project_id(id) {
        $('#activity_id').empty();
        $.ajax({
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
            },
            url: "{{url('ajax/get-assign-activity')}}",
            data: {
                project_id: id
            },
            success: function(xhr) {
                var datas = xhr.data;
                $('#activity_id').append('<option value="">--Select--</option>');
                for (var i = 0; i < datas.length; i++) {
                    $('#activity_id').append('<option value="' + datas[i].id + '">' + datas[i].activity_name +
                        '</option>');
                }
            }
        });
    }
</script>


<script>
    $(document).ready(function () {
        var datatable = $('#example').dataTable({
            ajax: "{{url('ajax/get-assign-task')}}",
            columns: [
                {data:'id',
                    render: function (data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                {data:'project_name'},
                {data:'start_date'},
                {data:'end_date'},
                {data:'activity_name'},
                {data:'status'},
                {data: null,
                    mRender:function ( data, type, row ) {
                        return '<a href="{{url("add-assign-task")}}/'+data.id+'" class="text-primary mx-2"><i class="fa fa-edit"></i></a>'+
                        '<a href="{{url("assign-task-delete")}}/'+data.id+'" class="text-danger delete-button"><i class="fa fa-trash"></i></a>';
                    }
                },
            ]
        });
    });
</script> -->


   


    <script>
        $(document).ready(function() {
            $('#activity_id').select2();
            $('#user_id').select2();
        });
    </script>
@endsection('content')