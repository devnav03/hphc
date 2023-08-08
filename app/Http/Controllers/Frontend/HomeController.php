<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\UserOtp;
use App\Models\User;
use App\Models\EmployeeInfo;
use App\Models\AttendanceHistory;
use App\Models\EmpAttendance;
use App\Models\ShiftDuration;
use Intervention\Image\ImageManagerStatic as Image;
use Auth;
use DB;
//use thiagoalessio\TesseractOCR\TesseractOCR;

class HomeController extends Controller {
   
    public function index() {
        
       echo "Welcome To HRMS"; exit;
        
    }

    public function reportSync(Request $request){
       
        $start_date = date('Y-m-d');
        $end_date = date('Y-m-d');
        $page = 1;
        $all_reports = $this->sendFaceCheckAlotte($start_date,$end_date,$page);
        
        
        
     
        $record=[];
        if(isset($all_reports['total_pages']) && $all_reports['total_pages'] > 0){
            $total_page=$all_reports['total_pages'];
            for($i=1; $i <= $total_page; $i++){
                $all_reports = $this->sendFaceCheckAlotte($start_date,$end_date,$i);
                if(!empty($all_reports['data'])){
                    foreach($all_reports['data'] as $data){
                    	$data['update_in_type']='ams';
                        $data['update_out_type']='ams';
                        array_push($record,$data);

                        // if (strpos($data['employee_id'], 'vs') !== false) {                         
                        //     $data['update_in_type']='ams';
                        //     $data['update_out_type']='ams';
                        //     array_push($record,$data);
                        // }
                        // if (strpos($data['employee_id'], 'vms') !== false) {                            
                        //     $data['update_in_type']='ams';
                        //     $data['update_out_type']='ams';
                        //     array_push($record,$data);
                        // }
                    }
                }
                
            }
        }
    
        // $cidata_ob= 'CID33';
        $VisitorHistory_data= AttendanceHistory::where(['last_synchronize_date'=>$start_date])->first();
     
        if(!isset($VisitorHistory_data->last_synchronize_date)){
            
            $VisitorHistory=new AttendanceHistory();
            // $VisitorHistory->company_id = $cidata_ob;
            $VisitorHistory->ams_data=json_encode($record);
            $VisitorHistory->last_synchronize_date=$start_date;
            $VisitorHistory->save();         
            
        }else{
            $new_record=[];
            $all_data = json_decode($VisitorHistory_data->ams_data);
            $visitor_ids=array_column((array)$all_data,'employee_id');
            $delete_employee=[];

            foreach($record as $key => $data ){
                if(in_array($data['employee_id'],$visitor_ids)){

                    $user_visitor = EmployeeInfo::where('employee_code', $data['employee_id'])->select('user_id as id')->first();

                    if(!empty($user_visitor)){
                    $date = date('Y-m-d');
                    
                  //  dd($user_visitor->id);
                    $all_visit_update = EmpAttendance::where('user_id', $user_visitor->id)->whereRaw('date_format(created_at,"%Y-%m-%d")'."='".$date . "'")->first();
                    $date = date('d-m-Y');
                    $in_time = '';
                    $out_time = '';
                    

                    if(@$data['in_time']){
                    
                    $in_time = str_replace($date,"",@$data['in_time']);
                    $in_time = str_replace("am",":00",@$in_time);
                    $in_time = str_replace("pm",":00",@$in_time);
                    $in_time = str_replace(" ","",@$in_time);

                    if(@$data['out_time']){
	                    $out_time = str_replace($date,"",@$data['out_time']);
	                    $out_time = str_replace("am",":00",@$out_time);
	                    $out_time = str_replace("pm",":00",@$out_time);
	                    $out_time = str_replace(" ","",@$out_time);
                    }


                    if($all_visit_update){
                        // $all_visit_update->in_time = $in_time;
	                    // $all_visit_update->in_device = @$data['in_device'];
	                    // $all_visit_update->in_status = @$data['in_time']?'Yes':'';
                        // $date = date('Y-m-d');
                        // $curren_time = date('H:i:s');

                        // $attendance = DB::select("SELECT id,TIMEDIFF('$curren_time',in_time) as totaltime from `emp_attendances` WHERE DATE(created_at) = '$date' AND user_id=$user_visitor->id LIMIT 1");
                       $seconds = @$data['actual_work_time']*60;
                       $H = floor($seconds / 3600);
                       $i = ($seconds / 60) % 60;
                       $s = $seconds % 60;
                       $totaltime = sprintf("%02d:%02d:%02d", $H, $i, $s);
	                    $all_visit_update->out_device = @$data['out_device'];
	                    $all_visit_update->out_time = $out_time;
                       $all_visit_update->out_image = @$data['out_time_image'];
	                    $all_visit_update->total_time = $totaltime;
	                    $all_visit_update->out_status = @$data['out_time']?@'Yes':'';
	                    $all_visit_update->save();

                    } else {
                        
                       $user = User::where('id', $user_visitor->id)->select('shift_id')->first();
                       $shift = ShiftDuration::where('shift_id', @$user->shift_id)->select('in_time_relaxation', 'out_time_relaxation')->first();

                       $all_visit_update =  (new EmpAttendance);
                       $all_visit_update->user_id = $user_visitor->id;
                       $all_visit_update->in_time = $in_time;
	                    $all_visit_update->in_device = @$data['in_device'];
	                    $all_visit_update->in_status = @$data['in_time']?'Yes':'';
	                    $all_visit_update->out_device = @$data['out_device'];
	                    $all_visit_update->out_time =  $out_time;
                       $all_visit_update->in_image = @$data['in_time_image'];
	                    $all_visit_update->out_status = @$data['out_time']?@'Yes':'';
                       $all_visit_update->start_date = @$shift->in_time_relaxation;  
                       $all_visit_update->end_date = @$shift->out_time_relaxation; 
	                    $all_visit_update->save();

                    }
                    
                    }
                    }
                    
                    if(isset($all_data[$key])){
                        //dd($data);
                        $all_data[$key] = $data;
                        array_push($new_record,$all_data[$key]);
                    }
                    
                    
                }else{
                    // if($data['update_in_type']=="ams"){
                        $data['in_time']= $data['in_time'];
                        $data['in_device']=$data['in_device'];
                        $data['update_in_type']='ams';
                    // }
                    // if($data['update_out_type']=="ams"){
                        $data['out_time']=$data['out_time'];
                        $data['out_device']=$data['out_device'];
                        $data['update_out_type']='ams';
                    // }
                    // if($data['in_time'] !="NA" && $data['out_time'] !="NA"){
                    //     array_push($delete_employee,$data['employee_id']);
                    // }
                    array_push($new_record,$data);
                    if(isset($all_data[$key])){
                        $all_data[$key] = $data;
                        array_push($new_record,$all_data[$key]);
                    }
                    
                }
                
            }
            foreach($delete_employee as $delete){
                //$this->deleteUser($delete);
            }
            if(!empty($new_record)){
                //dd($new_record);
                $VisitorHistory= AttendanceHistory::where(['last_synchronize_date'=>$start_date])->update(['ams_data'=>json_encode($new_record)]);
            }
            
        }
        return response()->json(['message'=>'Your Request Successfully Submitted', 'class'=>'success']);
    }

     function sendFaceCheckAlotte($start_date,$end_date,$page_no){
            $curl = curl_init();
            curl_setopt_array($curl, array(
              CURLOPT_URL => 'http://10.146.19.59:8000/api/public/simplified',
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS =>'{
              "start_date": "'.$start_date.'",
              "end_date": "'.$end_date.'",
              "page": "'.$page_no.'"
            }',
              CURLOPT_HTTPHEADER => array(
                'Authorization:bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX25hbWUiOiJoY2hwLmFkbWluIiwidHlwZV9vZl91c2VyIjoiQURNSU4iLCJ0b2tlbiI6IiQyYSQwOCQ1Zk5KY0d5SEJjcWVVQnI0a01TVkdlQlpxS3RsSnhHZ2JPbGR4OTRQbTRobENoTzJjQ0VDUyIsImlhdCI6MTY4MTk4NTcyOH0.hEGew-TGnrzBXDNa2m0IwLgJV1kgoVwsnrReezrOTKM',
              'Content-Type: text/plain'
              ),
            ));

            $response = curl_exec($curl);
            curl_close($curl);
           // dd($response);
            return json_decode($response,true);
    } 


}
