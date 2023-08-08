<!DOCTYPE html>
<html lang="en">
<head>
  <title>withdraw Leave Approval</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body style="padding:5px">
    <div style="width:100%;border:1px solid grey">
        <div style="background:#f7f0f0;padding:20px">
            <p>Hi <b>{{$authority_user_name}}</b>,<br/><br/> Employee <b>{{ $leave_applied_user_name}}</b> is withdraw <b>{{$leave_type}}</b> which is taken for <b>{{$leave_start_date}} to {{$leave_end_date}} </b>. Please verify employee is present in office </p>
        </div>       
    </div>
</body>
</html>
