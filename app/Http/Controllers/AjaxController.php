<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Organisation;
use App\Models\City;
use App\Models\EmpAttendance;
use App\Models\Leave;
use App\Models\ProjectActivity;
use App\Models\Timeseet;
use App\Models\EmpDetail;
use App\Models\SourceMaster;
use App\Models\PositionMaster;
use App\Models\NoticeMaster;
use App\Models\EducationMaster;
use App\Models\EmpDocument;
use App\Models\LetterTemplate;
use App\Models\ProjectMaster;
use App\Models\FormEngineCategory;
use App\Models\OfficeMaster;
use App\Models\DepartmentMaster;
use App\Models\ShiftMaster;
use App\Models\HeaderFooterMaster;
use App\Models\HeaderFooterTemplateMaster;
use App\Models\LeaveType;
use App\Models\EmpType;
use App\Models\State;
use App\Models\EmployeeInfo;
use App\Models\AssignTask;
use App\Models\FormEngine;
use App\Models\EmailTemplate;
use App\Models\SmsTemplate;
use App\Models\NotificationTemplate;
use App\Models\WeekDay;
use App\Models\TemplateMaster;
use App\Models\ResourceRequirement;
use App\Models\LeaveAuthority;
use App\Models\FlowMaster;
use App\Models\ApprovalFlow;
use App\Models\InterviewHistory;
use App\Models\NotificationSetting;
use App\Models\InterviewDocument;
use App\Models\HiringApproval;
use App\Models\InterviewHiringStatu;
use Illuminate\Support\Str;

use Auth;
//use DB;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
class AjaxController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    //public $from_email = "vikaspyadava@gmail.com";
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
     
     public function emailTemplateStatus(Request $request){
        $user_id = Auth::user()->id;
        if(TemplateMaster::where('orgnization_id', $user_id)->exists()){
            TemplateMaster::where('orgnization_id', $user_id)->update(['email_template' => $request->email_template]);
        }else{
            $template_master = new TemplateMaster();
            $template_master->orgnization_id = $user_id;
            $template_master->email_template = $request->email_template;
            $template_master->save();
        }
        return response()->json(['status'=>200,'message'=>'Successfully Saved']);
    }

    public function emailTemplateSetting(Request $request){ 
        $user_id = Auth::user()->id;
        if(TemplateMaster::where('id', $request->id)->where('orgnization_id', $user_id)->exists()){
            TemplateMaster::where('id', $request->id)->where('orgnization_id', $user_id)->update(['email_template' => $request->email_template]);
        }
        return response()->json(['status'=>200,'message'=>'Successfully Saved']);
    }

    public function smsTemplateSetting(Request $request){ 
        $user_id = Auth::user()->id;
        if(TemplateMaster::where('id', $request->id)->where('orgnization_id', $user_id)->exists()){
            TemplateMaster::where('id', $request->id)->where('orgnization_id', $user_id)->update(['sms_template' => $request->sms_template]);
        }
        return response()->json(['status'=>200,'message'=>'Successfully Saved']);
    }

    public function notificationTemplateSetting(Request $request){ 
        $user_id = Auth::user()->id;
        if(TemplateMaster::where('id', $request->id)->where('orgnization_id', $user_id)->exists()){
            TemplateMaster::where('id', $request->id)->where('orgnization_id', $user_id)->update(['notification_template' => $request->notification_template]);
        }
        return response()->json(['status'=>200,'message'=>'Successfully Saved']);
    }


    public function PostSortable(Request $request){
        $posts = FormEngine::all();
        foreach ($posts as $post) {
            $sele = FormEngine::where('id',$post->id)->first();
            if($sele->order_id==0){
                $sele->order_id = $sele->id;
                $sele->save();
            }
            foreach ($request->order as $order) {
                $sele = FormEngine::where('id',$order['id'])->first();
                if(!empty($sele)){
                    $sele->order_id = $order['position'];
                    $sele->save();
                }
            }
        }
        return response()->json(['status'=>400]);
    }

    public function smsTemplateStatus(Request $request){
        $user_id = Auth::user()->id;
        if(TemplateMaster::where('orgnization_id', $user_id)->exists()){
            TemplateMaster::where('orgnization_id', $user_id)->update(['sms_template' => $request->sms_template]);
        }else{
            $template_master = new TemplateMaster();
            $template_master->orgnization_id = $user_id;
            $template_master->sms_template = $request->sms_template;
            $template_master->save();
        }
        return response()->json(['status'=>200,'message'=>'Successfully Saved']);
    }
    public function notificationTemplateStatus(Request $request){
        $user_id = Auth::user()->id;
        if(TemplateMaster::where('orgnization_id', $user_id)->exists()){
            TemplateMaster::where('orgnization_id', $user_id)->update(['notification_template' => $request->notification_template]);
        }else{
            $template_master = new TemplateMaster();
            $template_master->orgnization_id = $user_id;
            $template_master->notification_template = $request->notification_template;
            $template_master->save();
        }
        return response()->json(['status'=>200,'message'=>'Successfully Saved']);
    }
    public function GetEmailTemplateList(Request $request){
        $user_id = Auth::user()->id;
        $data = EmailTemplate::select('*')->orderBy('id', 'DESC')->get();
        return response()->json(['data' => $data]);
    }
    public function getTemplateMasters(Request $request){
        $user_id = Auth::user()->id;
        $data = EmailTemplate::select('*')->orderBy('id', 'DESC')->get();
        return response()->json(['data' => $data]);
    }






    public function GetSMSTemplateList(Request $request){
        $user_id = Auth::user()->id;
        $data = SmsTemplate::select('*')->orderBy('id', 'DESC')->get();
        return response()->json(['data' => $data]);
    }
    public function GetNotificationemplateList(Request $request){
        $user_id = Auth::user()->id;
        $data = NotificationTemplate::select('*')->orderBy('id', 'DESC')->get();
        return response()->json(['data' => $data]);
    }
    public function GetStatusNotificationemplate(Request $request){
        $user_id = Auth::user()->id;
        $data = NotificationTemplate::select('id','status')->where('orgnization_id',$user_id)->where('id',$request->id)->first();
        $data->status = $request->status;
        $data->save();
        if(!empty($data)){
            return response()->json(['status'=>200,'data'=>$data]);
        }else{
            return response()->json(['status'=>400,'data'=>'']);
        }
    }
    public function GetStatusEmailTemplate(Request $request){
        $user_id = Auth::user()->id;
        $data = EmailTemplate::select('id','status')->where('orgnization_id',$user_id)->where('id',$request->id)->first();
        $data->status = $request->status;
        $data->save();
        if(!empty($data)){
            return response()->json(['status'=>200,'data'=>$data]);
        }else{
            return response()->json(['status'=>400,'data'=>'']);
        }
    }
    public function GetStatusSMSTemplate(Request $request){
        $user_id = Auth::user()->id;
        $data = SmsTemplate::select('id','status')->where('orgnization_id',$user_id)->where('id',$request->id)->first();
        $data->status = $request->status;
        $data->save();
        if(!empty($data)){
            return response()->json(['status'=>200,'data'=>$data]);
        }else{
            return response()->json(['status'=>400,'data'=>'']);
        }
    }
    public function HeaderTemplate(Request $request){ 
        $user_id = Auth::user()->id;

        $data = new HeaderFooterMaster();
        $data->orgnization_id = Auth::user()->id;
        dd($_FILES["header_image"]["name"]);
        if(!empty($_FILES['header_image']['name'])){
            $headerfilenames = time().'.'.$request->header_image->extension();
            $request->header_image->move(public_path('organization/header_image'),$headerfilenames);
            if(!empty($headerfilenames)){
                $data->header_image = $headerfilenames;
            }
        }
        if($request->hasFile('footer_image')){
            $footerfilenames = time().'.'.$request->footer_image->extension();
            $request->footer_image->move(public_path('organization/footer_image'),$footerfilenames);
            if(!empty($footerfilenames)){
                $data->footer_image = $footerfilenames;
            }
        }
        $data->save();

        if(!empty($data)){
            return response()->json(['status'=>200,'data'=>$data]);
        }else{
            return response()->json(['status'=>400,'data'=>'']);
        }
    }
    public function GetReportingUser($user_id){
        $reporting = DB::select("SELECT a.orgnization_id,a.reporting_id,b.email as report_email,b.name as report_name,c.name as org_name,c.email as org_email FROM `emp_reportings` as a INNER JOIN users as b on a.reporting_id=b.id INNER JOIN users as c on a.orgnization_id=c.id WHERE JSON_CONTAINS(a.employee_id,$user_id)=1");
        if(!empty($reporting[0])){
            return $reporting[0];
        }else{
            return array();
        }
    }
    public function SendAttendanceMail($data){
        $email = array($data->org_email, $data->report_email);
        try {
            $template_data = [
                'report_email'  => $data->report_email,
                'report_name'   => $data->report_name,
                'org_name'      => $data->org_name,
                'org_email'     => $data->org_email,
                'user_name'     => Auth::user()->name
            ];
            Mail::send(['html'=>'email.attendance'], $template_data,
                function ($message) use ($email,$template_data) {
                    $message->to($email)->from("vikaspyadava@gmail.com")->subject($template_data['user_name'].' marked attendance on '.date('d-M-Y'));
            });
            return true;
        } catch (Exception $ex) {
            return false;
        }  
    }
    public function SendAttendanceMailToEmployee($attendance_type){
        $emp = Auth::user();
        $email = $emp->email;
        try {
            $template = [
                'attendance_type'   => $attendance_type,
                'name'              => $emp->name
            ];
            Mail::send(['html'=>'email.emp_attendance'], $template,
                function ($message) use ($email,$template) {
                    $message->to($email)->from("vikaspyadava@gmail.com")->subject($template['name'].' marked '.$template['attendance_type'].' attendance on '.date('d-M-Y'));
            });
            return true;
        } catch (Exception $ex) {
            return false;
        }  
    }
	
	public function SendForwardMail($data){
		//echo "<pre>";print_r($data);echo "</pre>";
        try {
            
            $template_data = [
                'forwarded_by_name' => $data['forwarded_by_name'],
                'forwarded_by_email' => $data['forwarded_by_email'],
                'leave_applied_user_name' => $data['leave_applied_user_name'],
                'forwarded_to_name' => $data['forwarded_to_name'],
                'forwarded_to_email' => $data['forwarded_to_email']
            ];
            Mail::send(['html'=>'email.leave_forward'], $template_data,
                function ($message) use ($data) {
                   $message->to($data['forwarded_to_email'])->from('lnxxapp@gmail.com')->subject('Leave Forward ');
                   //$message->to('ankit.tiwari2093@gmail.com')->from('lnxxapp@gmail.com')->subject('Leave Forward ' );
            });
            return true;
        } catch (Exception $ex) {
            return false;
        }  
    }
	
    public function CheckEmail(Request $request){
        if (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            return response()->json(['status'=>401,'message'=>'Please enter valide email-id']);exit;
        }
        $slelect = User::where(['email'=>$request->email])->first();
        if(!empty($slelect)){
            return response()->json(['status'=>404,'message'=>'Unavailable !']);
        }else{
            return response()->json(['status'=>200,'message'=>'Available !']);
        }
    }
    public function CheckUsername(Request $request){
        $pattern = '/[\'\/~`\!@#\$%\^&\*\(\)_\-\+=\{\}\[\]\|;:"\<\>,\.\?\\\]/';
        if (preg_match($pattern, $request->user_name)){
            return response()->json(['status'=>401,'message'=>'Username should not contain any special characters, symbols or spaces']);exit;
        }
        $slelect = Organisation::where(['user_name'=>$request->user_name])->first();
        if(!empty($slelect)){
            return response()->json(['status'=>404,'message'=>'Unavailable !']);
        }else{
            return response()->json(['status'=>200,'message'=>'Available !']);
        }
    }
    public function OrganisationDetails(){
        $data = DB::select("SELECT a.id,a.name,b.user_name,a.email,a.mobile,b.logo,b.address,a.created_at,a.updated_at,b.status,b.id as m_id  FROM users as a INNER JOIN organisations as b on a.id=b.user_id WHERE type=1 ORDER BY a.id DESC");
        return response()->json(['data' => $data]);
    }
    public function EmployeeDetails(){
        $user_id = Auth::user()->id;
       // $data = DB::select("SELECT a.id,b.employee_code,a.name,a.email,a.mobile,a.status,a.created_at FROM `users` as a INNER JOIN employee_infos as b on a.id=b.user_id WHERE b.employee_code is NOT null AND a.organisation_id=$user_id ORDER BY a.id DESC");
       $data = DB::select("SELECT a.id,e.position_name,c.office_name,d.department_name,b.employee_code,a.name,a.email,a.mobile,a.status,a.created_at FROM `users` as a INNER JOIN employee_infos as b on a.id=b.user_id INNER JOIN office_masters as c on b.office_id = c.id INNER JOIN department_masters as d on b.department_id = d.id INNER JOIN position_masters as e on b.position_id = e.id WHERE b.employee_code is NOT null AND a.organisation_id=$user_id ORDER BY a.id DESC"); 
       return response()->json(['data' => $data]);
    }
    
    //28-11-2022 Ashutosh Start
    public function UserDetails(){
        $user_id = Auth::user()->id;
        $data = DB::select("SELECT a.id,a.name,a.email,a.mobile,a.created_at,a.updated_at,b.gender,b.dob,b.father_name,b.mother_name,b.profile FROM `users` as a INNER JOIN emp_details as b on a.id=b.user_id where b.user_id=$user_id ORDER BY a.id DESC");
        return response()->json(['data' => $data]);
    }
    public function UserContact(){
        $user_id = Auth::user()->id;
        $data = DB::select("SELECT a.id,a.name,a.email,a.mobile,a.created_at,a.updated_at,b.mobile,b.father_mobile,b.friend_mobile,b.address,b.pincode,c.name AS cityName,d.name AS stateName FROM users as a JOIN emp_contacts as b ON a.id = b.user_id JOIN cities as c ON c.id = b.city_id JOIN states as d ON d.id = b.state_id where b.user_id=$user_id ORDER BY a.id DESC;");
        return response()->json(['data' => $data]);
    }
    public function UserDocument(){
        $user_id = Auth::user()->id;
        $data = DB::select("SELECT a.id,a.name,a.email,a.mobile,a.created_at,a.updated_at,b.doucment_title,b.doucment_file FROM `users` as a INNER JOIN emp_documents as b on a.id=b.user_id where b.user_id=$user_id ORDER BY a.id DESC");
        return response()->json(['data' => $data]);
    }
    public function UserEducation(){
        $user_id = Auth::user()->id;
        $data = DB::select("SELECT a.id,a.name,a.email,a.mobile,a.created_at,a.updated_at,b.education_type,b.course_name,b.board_university,b.from_year,b.to_year,b.percentage_cgpa,b.document FROM `users` as a INNER JOIN emp_educations as b on a.id=b.user_id where b.user_id=$user_id ORDER BY a.id DESC");
        return response()->json(['data' => $data]);
    }
    public function UserBank(){
        $user_id = Auth::user()->id;
        $data = DB::select("SELECT a.id,a.name,a.email,a.mobile,a.created_at,a.updated_at,b.acc_holder_name,b.bank_id,b.acc_number,b.ifsc_code,b.pan_number,b.branch_name,b.status FROM `users` as a INNER JOIN emp_banks as b on a.id=b.user_id where b.user_id=$user_id ORDER BY a.id DESC");
        return response()->json(['data' => $data]);
    }
    public function UserCompany(){
        $user_id = Auth::user()->id;
        $data = DB::select("SELECT a.id,a.name,a.email,a.mobile,a.created_at,a.updated_at,b.comp_name,b.designation,b.date_of_joining,b.date_of_resignation,b.ctc,b.reason_for_leav_comp FROM `users` as a INNER JOIN emp_companies as b on a.id=b.user_id where b.user_id=$user_id ORDER BY a.id DESC");
        return response()->json(['data' => $data]);
    }
    public function UserAssetsRequestList(){
        $user_id = Auth::user()->id;
        $data = DB::select("SELECT a.id,a.start_date,a.end_date,a.description,b.assets_name,a.status FROM `assets_requests` AS a INNER JOIN assets_types AS b ON a.assets_type=b.id where a.user_id=$user_id ORDER BY a.id DESC");
        return response()->json(['data' => $data]);
    }
    public function viewAssetsData(Request $request){
        $data = DB::select("SELECT a.id,a.start_date,a.end_date,a.description,b.assets_name,c.name,e.employee_code,c.email,c.mobile,a.status,a.description_admin,a.start_date_admin,a.end_date_admin FROM `assets_requests` AS a INNER JOIN assets_types AS b ON a.assets_type=b.id INNER JOIN users AS c ON a.user_id=c.id INNER JOIN employee_infos as e on e.user_id=a.user_id WHERE e.employee_code is not null AND a.id=$request->id ORDER BY a.id DESC;");
        if(!empty($data[0])){
            return response()->json(['status'=>200,'data' => $data[0]]);
        }else{
            return response()->json(['status'=>400,'data' => []]);
        }
    }
    //28-11-2022 Ashutosh End

    //29-11-2022 Ashutosh Start
    public function UserLetterList(){
        $user_id = Auth::user()->id;
        $data = DB::select("SELECT * FROM letter_masters where user_id=$user_id");
        return response()->json(['data' => $data]);
    }
    public function UserOfficerSignatureList(){
        $user_id = Auth::user()->id;
        $data = DB::select("SELECT * FROM officer_signatures where user_id=$user_id");
        return response()->json(['data' => $data]);
    }
    public function UserLetterTemplateList(){
        $user_id = Auth::user()->id;
        $data = DB::select("SELECT * FROM letter_templates where user_id=$user_id");
        return response()->json(['data' => $data]);
    }
    public function UserMapLetterTemplateList(){
        $user_id = Auth::user()->id;
        $data = DB::select("SELECT * FROM map_letter_templates where user_id=$user_id");
        return response()->json(['data' => $data]);
    }
    //29-11-2022 Ashutosh End
    
    public function GetState(Request $request){
        $data = State::select(['id','name'])->where(['country_id'=>$request->country_id])->orderBy('name', 'ASC')->get();
        return response()->json(['data' => $data]);
    }
    public function GetCity(Request $request){
        $data = City::select(['id','name'])->where(['state_id'=>$request->state_id])->orderBy('name', 'ASC')->get();
        return response()->json(['data' => $data]);
    }
    public function MarkAttendance(Request $request){
        $date = date('Y-m-d');
        $curren_time = date('H:i:s');
        $user_id = Auth::user();

        $image_64 = $request->snapshot;
        $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];
        $replace = substr($image_64, 0, strpos($image_64, ',')+1);
        $image = str_replace($replace, '', $image_64); 
        $image = str_replace(' ', '+', $image); 
        $imageName = str_replace(' ', '_',Str::lower(Auth::user()->name)).'_'.Str::lower(Str::random(10)).'.'.$extension;
        Storage::disk('attendance')->put($imageName, base64_decode($image));
        
        if(empty($request->latitude) && empty($request->longitude)){
            return response()->json(['status'=>400,'message'=>'Please Turn On Your Location']);exit;
        }

        $attendance = DB::select("SELECT id,TIMEDIFF('$curren_time',in_time) as totaltime from `emp_attendances` WHERE DATE(created_at) = '$date' AND user_id=$user_id->id LIMIT 1");
        if(!empty($attendance[0])){
            $emp_attendance = EmpAttendance::where(['id'=>$attendance[0]->id])->first();
            $emp_attendance->user_id = $user_id->id;
            $emp_attendance->out_time = $curren_time;
            $emp_attendance->out_image = $imageName;
            $emp_attendance->out_latitude = $request->latitude;
            $emp_attendance->out_longitude = $request->longitude;
            $emp_attendance->total_time = $attendance[0]->totaltime;
            $emp_attendance->save();
            $this->SendAttendanceMailToEmployee('out');
            $not['title']='Attendance Marked Successfully';

            $not['body']='Dear '.$user_id->name.' you have successfully marked attendance out';
            
            $this->SendPushNotification($not,$user_id,2);
            return response()->json(['status'=>200,'message'=>'Successfully Attancdance Marked Out','attendance'=>'OUT']);
        }else{
            $emp_attendance = new EmpAttendance();
            $emp_attendance->user_id = $user_id->id; 
            $emp_attendance->in_time = $curren_time;
            $emp_attendance->in_image = $imageName;
            $emp_attendance->in_latitude = $request->latitude;
            $emp_attendance->in_longitude = $request->longitude;
            $emp_attendance->save();
            $this->SendAttendanceMailToEmployee('in');
            $not['title']='Attendance Marked Successfully';
            $not['body']='Dear '.$user_id->name.' you have successfully marked attendance in';
            $this->SendPushNotification($not,$user_id,2);
            return response()->json(['status'=>200,'message'=>'Successfully Attancdance Marked In','attendance'=>'IN']);
        }
    }
    public function EmployeeAttendances(){
        $data = EmpAttendance::select('id','user_id','in_time','total_time','out_time','in_image','out_image','created_at')->where(['user_id'=>Auth::user()->id])->orderBy('id', 'DESC')->get();
        return response()->json(['data' => $data]);
    }
    public function AllEmployeeAttendances(){
        $orgnaization = Auth::user()->id;
        $emp_detail = EmpDetail::select('user_id')->where('created_by',$orgnaization)->get();
        if(!empty($emp_detail)){
            foreach($emp_detail as $row){
                $data[]=$row->user_id;
            }
            $users_id = implode(',',$data);
            $data = EmpAttendance::select('id','user_id','in_time','total_time','out_time','in_image','out_image','created_at')->whereIn('user_id', $data)->orderBy('id', 'DESC')->get();
        }else{
            $data=[];
        }
        return response()->json(['data' => $data]);

        //tarika garalat hai
    }
	
	//////////////// Code changed by ankit //////////////////////
	
	/* public function EmployeeLeaves(){
        $user_id = Auth::user()->id;
        $data = DB::select("SELECT a.id,a.start_date,a.end_date,a.duration,a.reason_for_leav_comp,a.status,b.name FROM `leaves` as a INNER JOIN leave_types as b on a.leave_type=b.id WHERE a.user_id=$user_id ORDER BY a.id DESC");
        return response()->json(['data' => $data]);
    } */
	
	
    public function EmployeeLeaves(){
        $user_id = Auth::user()->id;
        $data = DB::select("SELECT a.id,a.start_date,a.end_date,a.withdraw_status,a.duration,a.reason_for_leav_comp,a.status,b.name FROM `leaves` as a INNER JOIN leave_types as b on a.leave_type=b.id WHERE a.user_id=$user_id ORDER BY a.id DESC");
       		
		$today_date = date('Y-m-d');
		$leave_withdraw_button_status = 1;
		
		$leave_array = [];
		if(count($data)){
			foreach($data as $record){
				
				$leave_end_date = $record->end_date; 
				if($today_date >= $leave_end_date && $record->status=='Approved'){
					$leave_withdraw_button_status = 0;
				}
				
				if($record->withdraw_status){
					$leave_withdraw_button_status = 0;
				}
				$record->withdraw_button = $leave_withdraw_button_status;
				
				$leave_array[] = $record;
			}		
		}		
		
		return response()->json(['data' => $leave_array]);
    }
	
	
	public function employeeLeaveStatus(Request $request){
			$user_id = Auth::user()->id;
		$leaves = Leave::where('id',$request->id)->first();
		$leaves->status = $request->status;
		$leaves->updated_by = $user_id;
		$leaves->save();
		$data = User::select('name','email')->where('id',$leaves->user_id)->first();
		$canceled_emp = User::select('name')->where('id',$user_id)->first();
		$reponce=(object)[
		'name'              =>$data->name,
		'email'             =>$data->email,
		'status'            =>($leaves->status=='Reject')?'Rejected':$leaves->status,
		'canceled_emp'      =>$canceled_emp->name,
		'from'              =>date_format(date_create($leaves->start_date),"d-M-Y"),
		'to'                =>date_format(date_create($leaves->end_date),"d-M-Y"),
		];
		$this->SendRegisterMail($reponce);
		return response()->json(['status'=>200,'data' => $leaves]);
	}
	
	public function SendRegisterMail($data){
		try {
			$orgnisation = Organisation::where(['user_id'=>Auth::user()->organisation_id])->first();
			$template_data = [
			'email' => $data->email,
			'name' => $data->name,
			'canceled_emp'=>$data->canceled_emp,
			'status'=>$data->status,
			'from'=>$data->from,
			'to'=>$data->to,
			'user_name'=>$orgnisation->user_name,
			];
			Mail::send(['html'=>'email.leave'], $template_data,
			function ($message) use ($data) {
				$message->to($data->email)->from('lnxxapp@gmail.com')->subject('Leave '.$data->status);
			});
			return true;
			} catch (Exception $ex) {
			return false;
		}  
	}
	
	public function updateTourLeaveEndDate(Request $request){
		$user = Auth::user();
		$user_id = Auth::user()->id;
		
		if($request->leave_user_id != $user_id){
			
			$update_leave = Leave::where('id', $request->leave_id)->update(['end_date'=>$request->end_date, 'duration'=>$request->duration, 'updated_by'=>$user_id]);
			
			if($update_leave){
				$resp = array('status'=>200, 'type'=>'success', 'message'=>"Updated Successfully");
			}else{
				$resp = array('status'=>200, 'type'=>'error', 'message'=>"Error updating in Date");
			}
			
		}else{
			$resp = array('status'=>200, 'type'=>'error', 'message'=>"Hi you are not eligible to update own leave end date. Please contact to Administrator");
		}
		return response()->json($resp);
	}
	
	//////////////// Code changed by ankit end //////////////////////

    public function GetLeaveReason(Request $request){
        $data = DB::select("SELECT a.id,a.start_date,a.end_date,a.duration,a.reason_for_leav_comp,a.created_at,a.status,b.name as leave_type,c.name,c.mobile FROM `leaves` as a INNER JOIN leave_types as b on a.leave_type=b.id INNER JOIN users as c on c.id=a.user_id WHERE a.id=$request->id");
        if(!empty($data[0])){
            return response()->json(['status'=>200,'data' => $data[0]]);
        }else{
            return response()->json(['status'=>400,'data' => []]);
        }
    }
    public function GetActivity(Request $request){
        $data = ProjectActivity::select('id','activity_name')->where(['id'=>$request->project_id])->get();
        return response()->json(['status'=>200,'data' => $data]);
    }
    public function ViewTimesheet(){
        $user_id = Auth::user()->id;
        $data = DB::select("SELECT a.id,a.start_time,a.end_time,a.duration,a.description,a.status,b.project_name,c.activity_name,a.created_at FROM `timeseets` as a INNER JOIN project_masters as b on a.project_id=b.id INNER JOIN project_activities as c on b.id=c.project_id WHERE a.user_id=$user_id GROUP BY a.id DESC");
        return response()->json(['data' => $data]);
    }
    public function GetTimesheetData(Request $request){
        $data = Timeseet::select('description')->where(['id'=>$request->id])->first();
        return response()->json(['status'=>200,'data' => $data]);
    }
    public function GetSourceMasters(){
        $user_id = Auth::user()->id;
        $data = SourceMaster::where(['orgnization_id'=>$user_id])->orderBy('id', 'DESC')->get();
        return response()->json(['status'=>200,'data' => $data]);
    }
    public function GetNoticeMasters(){
        $user_id = Auth::user()->id;
        $data = DB::select("SELECT a.id,a.notice_days,a.is_default,a.status,a.created_at,a.updated_at,b.office_name,c.department_name,d.position_name FROM `notice_masters` AS a INNER JOIN office_masters AS b ON b.id=a.office_id INNER JOIN department_masters AS c ON c.id=a.department_id INNER JOIN position_masters AS d ON d.id=a.position_id WHERE a.orgnization_id=$user_id ORDER BY a.id DESC");
        return response()->json(['status'=>200,'data' => $data]);
    }
    public function GetEducationMasters(){
        $user_id = Auth::user()->id;
        $data = DB::select("SELECT a.is_default,a.id,a.education_title,a.status,a.created_at,a.updated_at,b.office_name,c.department_name,d.position_name FROM `education_masters` AS a INNER JOIN office_masters AS b ON b.id=a.office_id INNER JOIN department_masters AS c ON c.id=a.department_id INNER JOIN position_masters AS d ON d.id=a.position_id WHERE a.orgnization_id=$user_id ORDER BY a.id DESC");
        return response()->json(['status'=>200,'data' => $data]);
    }
    public function GetEmployeeAllDetails(Request $request){
        $user_id = $request->segment(3);
        $orgnaization = Auth::user()->id;
        $form_category = FormEngineCategory::select('id','name','is_multiple')->where('orgnization_id', $orgnaization)->orderBy('is_multiple', 'ASC')->get();
        foreach($form_category as $formcategory){
            $data = EmployeeInfo::select('datas')->where('organisation_id',$orgnaization)->where('user_id',$user_id)->where('from_cat_id',$formcategory->id)->first();
            if($formcategory->is_multiple==1){
                $emp = @json_decode($data->datas);
                ?>
                <div class="col-md-12 mb-3">
                    <div class="card mb-4 mb-md-0">
                        <div class="card-body">
                            <h5 class="mb-2"> <?=$formcategory->name;?> </h5>
                            <div class="row">
                                <table class="table table-condensed">
                                    <thead>
                                        <tr>
                                        <?php if(!empty($emp)){ foreach($emp as $x => $val){
                                            $form = FormEngine::select('form_name')->where('form_column',$x)->first();
                                            echo '<td><b>'.$form->form_name.'</b></td>';
                                        } } ?>
                                        </tr>
                                    </thead>
                                    <tbody><tr>
                                        <?php if(!empty($emp)){ foreach($emp as $x => $val){
                                            $form = FormEngine::select('form_name','data_type')->where('form_column',$x)->first();
                                            $count = count($val);
                                            echo '<td>';
                                            for($i=0;$i < $count;$i++){
                                                if($form->data_type=='file'){
                                                    echo '<p><a href="'.url(@$val[$i]).'" download>Download</a></p>';
                                                }elseif($form->data_type=='date'){
                                                    echo '<p >'.date_format(date_create(@$val[$i]),"d-M-Y").'</p>';
                                                }else{
                                                    echo '<p >'.@$val[$i].'</p>';
                                                }
                                            }
                                            echo '</td>';
                                        } } ?></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            <?php }else{ $emp1 = @json_decode($data->datas);?>
                <div class="col-md-6 mb-3">
                    <div class="card mb-4 mb-md-0">
                        <div class="card-body">
                            <h5 class="mb-2"> <?=$formcategory->name;?> </h5>
                            <div class="row">
                                <table class="table table-condensed"><tbody>
                                <?php if(!empty($emp1)){    foreach($emp1 as $x => $val){
                                    
                                    $form = FormEngine::select('form_name','data_type')->where('form_column',$x)->first();
                                    if(!empty($form)){
                                    if($form->data_type=='file'){
                                        $valdata = '<p><a href="'.url(@$val).'" download>Download</a></p>';
                                    }elseif($form->data_type=='date'){
                                        $valdata = '<p >'.date_format(date_create(@$val),"d-M-Y").'</p>';
                                    }else{
                                        $valdata = '<p >'.@$val.'</p>';
                                    }
                                    echo '<tr>
                                            <td><b>'.$form->form_name.'</b></td>
                                            <td>'.$valdata.'</td>
                                        </tr>';
                                  }
                                } } ?>
                                </tbody></table>
                            </div>
                        </div>
                    </div>
                </div>
            <?php }
        }
    }
    // public function GetEmployeeAllDetails(Request $request){
    //     $user_id = $request->segment(3);

    //     $personal = DB::select("SELECT a.name,a.email,a.mobile,b.gender,b.dob,b.father_name,b.mother_name,b.profile,b.salary,c.position_name,d.source_name,e.notice_days FROM `users` as a INNER JOIN emp_details as b on a.id=b.user_id INNER JOIN position_masters as c on b.designation_id=c.id INNER JOIN source_masters as d on b.source_id=d.id INNER JOIN notice_masters as e on b.notice_id=e.id WHERE a.id=$user_id");

    //     $contact = DB::select("SELECT mobile,father_mobile,friend_mobile,address,pincode,stateName,cityName FROM `emp_contacts` as a INNER JOIN states as b on a.state_id=b.stateID INNER JOIN cities as c on a.city_id=c.cityID WHERE a.user_id=$user_id");

    //     $bank = DB::select("SELECT a.acc_holder_name,b.name,a.acc_number,a.ifsc_code,a.pan_number,a.branch_name FROM `emp_banks` as a INNER JOIN bank_masters as b on a.bank_id=b.id WHERE a.user_id=$user_id");

    //     $education = DB::select("SELECT b.education_title,a.course_name,a.board_university,a.from_year,a.to_year,a.percentage_cgpa,a.document FROM `emp_educations` as a INNER JOIN education_masters as b on a.education_type=b.id WHERE a.user_id=$user_id");

    //     $companies = DB::select("SELECT comp_name,designation,date_of_joining,date_of_resignation,ctc,reason_for_leav_comp FROM `emp_companies` WHERE user_id=$user_id ORDER BY id DESC");

    //     $emp_document = EmpDocument::select('doucment_title','doucment_file')->where('user_id',$user_id)->get();

    //     return response()->json([
    //         'status'=>200,
    //         'personal' => !empty($personal[0]) ? $personal[0]:[],
    //         'contact' => !empty($contact[0]) ? $contact[0]:[],
    //         'bank' => !empty($bank[0]) ? $bank[0]:[],
    //         'education' => !empty($education) ? $education:[],
    //         'companies' => !empty($companies) ? $companies:[],
    //         'emp_document' => !empty($emp_document) ? $emp_document:[],
    //     ]);
    // }
    public function GetEmployeeAttendanceData(Request $request){
        $user_id = Auth::user()->id;
        $data = DB::select("SELECT a.*,CONCAT(c.employee_code, ' - ', b.name) AS names,d.office_name,e.department_name FROM `emp_attendances` as a INNER JOIN users as b on a.user_id=b.id INNER JOIN employee_infos as c on a.user_id=c.user_id INNER JOIN office_masters as d on d.id=c.office_id INNER JOIN department_masters as e on e.id=c.department_id WHERE c.employee_code is NOT null AND c.organisation_id=$user_id AND c.office_id=$request->office_id AND c.department_id=$request->department_id AND a.user_id=$request->emp_id ORDER BY a.id DESC");
        return response()->json(['status'=>200,'data' => $data]);
    }
    public function GetEmployeeAttendanceDetails(Request $request){
        $user_id = Auth::user()->id;
        if(!empty($request->emp_id)){
           $data = DB::select("SELECT a.in_status,a.out_status,a.id,b.organisation_id,c.employee_code,b.name,a.user_id,a.in_time,a.total_time,a.out_time,a.in_image,a.out_image,a.created_at FROM emp_attendances as a INNER JOIN users as b on a.user_id=b.id INNER JOIN employee_infos as c on b.id = c.user_id WHERE a.user_id=$request->emp_id AND MONTH(a.created_at)=$request->month AND YEAR(a.created_at)=$request->year group by a.user_id ORDER BY id DESC");
            //$data = DB::select("SELECT id,user_id,in_time,total_time,out_time,in_image,out_image,created_at FROM emp_attendances WHERE user_id=$request->emp_id AND MONTH(created_at)=$request->month AND YEAR(created_at)=$request->year ORDER BY id DESC");
        }else{
             $data = DB::select("SELECT a.in_status,a.out_status,a.id,b.organisation_id,c.employee_code,b.name,a.user_id,a.in_time,a.total_time,a.out_time,a.in_image,a.out_image,a.created_at FROM emp_attendances as a INNER JOIN users as b on a.user_id=b.id INNER JOIN employee_infos as c on b.id = c.user_id WHERE b.organisation_id =$user_id group by a.user_id ORDER BY id DESC");
   //  $data = DB::select("SELECT id,user_id,in_time,total_time,out_time,in_image,out_image,created_at FROM emp_attendances ORDER BY id DESC");
        }
        return response()->json(['status'=>200,'data' => $data]);
    }
    
    
    public function GetLetterPreview(Request $request){
        $data = LetterTemplate::select('description')->where('id',$request->id)->first();
        return response()->json(['status'=>200,'data' => $data]);
    }
    public function GetProjectMaster(){
        $user_id = Auth::user()->id;
        $data = DB::select('SELECT a.project_name,a.task_master,a.start_date,a.end_date,a.id,a.status,b.office_name,c.department_name FROM `project_masters` AS a INNER JOIN office_masters AS b ON a.office_id=b.id INNER JOIN department_masters AS c ON a.department_id=c.id WHERE a.orgnization_id='.$user_id.' ORDER BY a.id DESC');
        return response()->json(['status'=>200,'data' => $data]);
    }
    public function ViewEmpTimesheet(Request $request){
        $user_id = Auth::user()->id;
        $data = DB::select("SELECT d.name,a.id,a.start_time,a.end_time,a.duration,a.description,a.status,b.project_name,c.activity_name,a.created_at FROM `timeseets` as a INNER JOIN project_masters as b on a.project_id=b.id INNER JOIN project_activities as c on b.id=c.project_id INNER JOIN users as d on d.id=a.user_id WHERE a.user_id=$request->emp_id AND MONTH(a.created_at)=$request->month AND YEAR(a.created_at)=$request->year GROUP BY a.id DESC");
        return response()->json(['data' => $data]);
    }
    public function SaveProjectActivities(Request $request){
        $project = new ProjectActivity();
        $project->project_id = $request->project_id;
        $project->activity_name = $request->activity_name;
        $project->save();
        return response()->json(['status'=>200,'message'=>'Successfully Saved']);
    }
    public function GetActivitiesList($id){
        $data = ProjectActivity::where('project_id',$id)->get();
        return response()->json(['data' => $data]);
    }
    public function DeleteProjectActivities($id){
        ProjectActivity::where('id',$id)->delete();
        return response()->json(['status'=>200,'message'=>'Successfully Deleted']);
    }
    public function GetReporting(Request $request){
        $user_id = Auth::user()->id;
        $data = EmpDetail::select('user_id','first_name','last_name')->where('designation_id',$request->reporting)->where('created_by',$user_id)->orderBy('first_name', 'ASC')->get();
        return response()->json(['status'=>200,'data' => $data]);
    }
    public function ViewEmpAssignPro(Request $request){
        $user_id = Auth::user()->id;
        $data = DB::select("SELECT a.id,a.start_date,a.end_date,a.description,a.created_at,b.project_name,CONCAT(c.first_name,' ',c.last_name) as name FROM `emp_projects` as a INNER JOIN project_masters as b on a.project_id=b.id JOIN emp_details AS c on c.user_id=a.employee_id WHERE b.orgnization_id=$user_id AND c.user_id=$request->employee_id GROUP BY a.id DESC");
        return response()->json(['data' => $data]);
    }
    public function GetFormEngineMasters(){
        $user_id = Auth::user()->id;
        $data = FormEngineCategory::where('orgnization_id',$user_id)->orderBy('name', 'ASC')->get();
        return response()->json(['status'=>200,'data' => $data]);
    }
	
	public function GetRoleMasters(){
		$user_id = Auth::user()->id;
		$data = DB::select("SELECT * from roles");
		// $data = OfficeMaster::where(['orgnization_id'=>$user_id])->orderBy('id', 'DESC')->get();
		return response()->json(['status'=>200,'data' => $data]);
	}
	public function GetUserList(){
		$user_id = Auth::user()->id;
		$data = DB::select("SELECT a.id as user_id,roles.name roles_name , a.name as user_name,a.email as user_email,b.company_name as company_name FROM users as a LEFT OUTER JOIN organisations as b ON a.id=b.user_id LEFT OUTER JOIN role_user ON a.id=role_user.user_id LEFT OUTER JOIN roles ON role_user.role_id=roles.id
		");
		// $data = OfficeMaster::where(['orgnization_id'=>$user_id])->orderBy('id', 'DESC')->get();
		return response()->json(['status'=>200,'data' => $data]);
	}
		
			
    public function GetOfficeMasters(){
        $user_id = Auth::user()->id;
        $data = DB::select("SELECT a.id,a.office_name,a.status,a.created_at,a.updated_at,b.name as city,c.name as state FROM `office_masters` AS a INNER JOIN cities AS b ON a.city_id=b.id INNER JOIN states AS c ON c.id=a.state_id WHERE a.orgnization_id=$user_id ORDER BY a.id DESC");
        // $data = OfficeMaster::where(['orgnization_id'=>$user_id])->orderBy('id', 'DESC')->get();
        return response()->json(['status'=>200,'data' => $data]);
    }
    public function GetShiftMasters(){
        $user_id = Auth::user()->id;
        $data = ShiftMaster::where(['orgnization_id'=>$user_id])->orderBy('id', 'DESC')->get();
        return response()->json(['status'=>200,'data' => $data]);
    }

    /*--------VIKAS CODE START HERE-------*/

    public function GetHeaderFooterTemplateMasters(){
       $user_id = Auth::user()->id;  
       $data = DB::select("SELECT a.id,a.orgnization_id,b.office_name,a.header_image,a.footer_image,a.status,a.created_at,a.updated_at FROM `header_footer_template_masters` AS a INNER JOIN `office_masters` AS b ON a.office_id=b.id WHERE a.orgnization_id=$user_id ORDER BY a.id DESC");
        return response()->json(['status'=>200,'data' => $data]);
    } 
 
    public function ViewHeaderFooterData(Request $request){
       $data = DB::select("SELECT a.id,a.orgnization_id,b.office_name,a.header_image,a.footer_image,a.status,a.created_at,a.updated_at FROM `header_footer_template_masters` AS a INNER JOIN `office_masters` AS b ON a.office_id=b.id WHERE a.id=$request->id");
        if(!empty($data[0])){
            return response()->json(['status'=>200,'data' => $data[0]]);
        }else{
            return response()->json(['status'=>400,'data' => []]);
        }
    }
    public function GetStatusHeaderFooterTemplate(Request $request){
        $user_id = Auth::user()->id;
        $data = HeaderFooterTemplateMaster::select('id','status')->where('orgnization_id',$user_id)->where('id',$request->id)->first();
        $data->status = $request->status;
        $data->save();
        if(!empty($data)){
            return response()->json(['status'=>200,'data'=>$data]);
        }else{
            return response()->json(['status'=>400,'data'=>'']);
        }
    }


    public function GetStatusFlowData(Request $request){ 
        $user_id = Auth::user()->id;
        $data = FlowMaster::select('id','status')->where('orgnization_id',$user_id)->where('id',$request->id)->first();
        $data->status = $request->status;
        $data->save();
        if(!empty($data)){
            return response()->json(['status'=>200,'data'=>$data]);
        }else{
            return response()->json(['status'=>400,'data'=>'']);
        }
    }




    /*--------VIKAS CODE END HERE-------*/

    public function GetEmployeeLeaveData(Request $request){
        $status='';
        $user='';
        $department='';
        if(!empty($request->department_id)){
            $department = "AND a.department_id=$request->department_id";
        }if(!empty($request->status)){
            $status = "AND a.status='$request->status'";
        }
        if(!empty($request->user_id)){
            $user = "AND a.user_id=$request->user_id";
        }

        $data = DB::select("SELECT a.id,a.start_date,a.end_date,a.duration,a.time_duration,a.status,b.employee_code,c.name,d.name as leave_type,a.created_at FROM `leaves` as a INNER JOIN employee_infos as b on a.user_id=b.user_id INNER JOIN users as c on c.id=b.user_id INNER JOIN leave_types as d on d.id=a.leave_type WHERE a.office_id=$request->office_id $user $department $status AND MONTH(a.created_at)='$request->month' AND YEAR(a.created_at)='$request->year' GROUP BY a.id ORDER BY a.id DESC");
       
		return response()->json(['status'=>200,'data' => $data]);
    }
    public function GetEmpApprovedLeaveData(Request $request){
        $data = DB::select("SELECT a.id,a.user_id,a.duration,a.status,a.created_at,b.name,c.name AS leave_type From leaves AS a INNER JOIN users AS b ON a.user_id=b.id INNER JOIN leave_types AS c on c.id=a.leave_type WHERE a.status='Approved' and a.user_id=$request->emp_id AND MONTH(a.created_at)=$request->month AND YEAR(a.created_at)=$request->year");
        return response()->json(['status'=>200,'data' => $data]);
    }
    public function GetEmpRejectLeaveData(Request $request){
        $data = DB::select("SELECT a.id,a.user_id,a.duration,a.status,a.created_at,b.name,c.name AS leave_type From leaves AS a INNER JOIN users AS b ON a.user_id=b.id INNER JOIN leave_types AS c on c.id=a.leave_type WHERE a.status='Reject' and a.user_id=$request->emp_id AND MONTH(a.created_at)=$request->month AND YEAR(a.created_at)=$request->year");
        return response()->json(['status'=>200,'data' => $data]);
    }
	
    public function ViewEmpLeaveData(Request $request){
       
		$data = DB::select("SELECT a.id,a.office_id, a.department_id, a.forwarded_by, a.withdraw_status, a.updated_by,a.start_date,a.end_date,a.duration,a.time_duration,a.reason_for_leav_comp,a.created_at,a.status,b.id as leave_type_id, b.orgnization_id, b.name as leave_name, c.name,c.mobile, es.employee_code FROM `leaves` as a INNER JOIN leave_types as b on a.leave_type=b.id INNER JOIN users as c on c.id=a.user_id INNER JOIN employee_infos as es on es.user_id=a.user_id WHERE a.id=$request->id ");
         
		//echo "<pre>";print_r($data[0]);echo "</pre>";
		
		///////////////// code by ankit ///////////////////////////
		
		$today_date = date('Y-m-d');
		
		$send_status = '';
		$tracking_user = '';	
		
		$forwarded_by = $data[0]->forwarded_by;
		
		$updated_by = $data[0]->updated_by;
		$user_id = Auth::user()->id; 	
		
		$check_user_forwarded_request = strpos($forwarded_by,$user_id);	
		$forward_status = 0;
		if($check_user_forwarded_request!=''){
			$forward_status = 1;
		}
		
		$leave_start_date = $data[0]->start_date; 
		$leave_end_date = $data[0]->end_date; 
		$leave_duration = $data[0]->duration;
		
		$leave_withdraw_button_status = 1;
		
		if($today_date >= $leave_end_date){
			$leave_withdraw_button_status = 0;
		}
		
		if($data[0]->withdraw_status){
			$leave_withdraw_button_status = 0;
		}
		
		$officeId = $data[0]->office_id; 
		$department_id = $data[0]->department_id;
		$orgnization_id = $data[0]->orgnization_id;
		
		$leave_type_id = $data[0]->leave_type_id;
		
		$approval_count = 0;
		
		////////////// Get Flow_id for this leave by ankit ////////////////////
		//echo "SELECT * FROM `approval_flows` WHERE orgnization_id=$orgnization_id AND office_id='".$officeId."' AND department_id='".$department_id."' AND leave_type=$leave_type_id order by id DESC";
		
		
		$get_flow_data = DB::select("SELECT flow_id FROM `approval_flows` WHERE orgnization_id=$orgnization_id AND office_id='".$officeId."' AND department_id='".$department_id."' AND leave_type=$leave_type_id order by id DESC");
		
		if(!empty($get_flow_data[0])){
			
			$flow_id= $get_flow_data[0]->flow_id; 
			$data[0]->flow_id= $flow_id;
			
			////////////// Get Approval authority count for show forward button ////////////////////
			$data_approval = DB::select("SELECT * FROM `leave_authorities` WHERE flow_id = $flow_id AND orgnization_id=$orgnization_id AND office_id=$officeId");
			//echo "<pre>";print_r($data_approval);echo "</pre>";		
			
			if(!empty($data_approval[0])){			
				$approval_count = count($data_approval);				
			}else{
				$send_status = "There is no authority for this leave";
			}
		}
		
		
		///////////////// Checking employee leave, where is pending / Approved / Reject /////////////////////////
		$arr_ind = '';
		if($approval_count > 0 && $data[0]->status=='Pending'){
			if($forwarded_by ){
				$key = array_search($forwarded_by, array_column($data_approval, 'user_id'));
				//echo "forwarded";
				if($key > 0){
					////////////// key is subtract by 1 beacause leave is forwarded and key is forward record array key ///////////////////////
					$arr_ind = $key-1;
					$new_data_approval_array = $data_approval[$arr_ind];
				}else{
					$new_data_approval_array = $data_approval[$key];
				}		
				$leave_status_at_user = $new_data_approval_array->user_id;	
				
			}else{
				//echo "Not forwarded";
				$arr_ind = $approval_count-1;
				$new_data_approval_array = $data_approval[$arr_ind];

					
				$leave_status_at_user = $new_data_approval_array->user_id;									
			}
						
			$get_tracking_data = User::find($leave_status_at_user);	
			
			if(!empty($get_tracking_data) ){
				$tracking_user = $get_tracking_data->name;	
			}else{
				$tracking_user = "";	
			}
			
			$send_status = "Pending by ";
		}
		
		
		if($data[0]->status=='Approved' || $data[0]->status=='Reject'){
			$leave_status_at_user = $updated_by;
			$send_status = $data[0]->status. " by ";
			
			$get_tracking_data = User::find($leave_status_at_user);			
			$tracking_user = $get_tracking_data->name;	
		}
		
		
		$data[0]->approver_name = $send_status . $tracking_user;
		
        if(!empty($data[0])){
            return response()->json(['status'=>200,'data' => $data[0], 'approval_count'=> $approval_count, 'forward_status'=> $forward_status, 'withdraw_status'=> $leave_withdraw_button_status]);
        }else{
            return response()->json(['status'=>400,'data' => [], 'approval_count'=> $approval_count, 'forward_status'=> $forward_status, 'withdraw_status'=> $leave_withdraw_button_status]);
        }
    }
	
	public function ViewForwardUserData(Request $request){
		
		$user_id = Auth::user()->id;		
		$officeId = $request->office_id;
		$leave_id = $request->leave_id;
		
		$flow_id= $request->flow_id;
		
		////////////////////// Get User who forwarded the leave application /////////////////////////		
		
		$forwarded_leave_by_user = DB::select("SELECT forwarded_by FROM `leaves` WHERE id=$leave_id");
		
		$forwarded_user = $user_id;
		
		if(!empty($forwarded_leave_by_user[0]->forwarded_by)){
			$forwarded_user = $forwarded_user.','.$forwarded_leave_by_user[0]->forwarded_by;
		}
		
		////////////// Get user of forward authority ////////////////////
	
		$data = DB::select("SELECT a.id, b.id as user_id, b.name, b.mobile, b.email FROM `leave_authorities` as a INNER JOIN users as b on b.id=a.user_id WHERE a.flow_id=$flow_id AND a.office_id=$officeId AND a.user_id NOT IN ($forwarded_user) order by a.id asc");
		
		$html ='';
		if(count($data) > 0){
		
			foreach($data as $user_details){
			
				//////////////////// $user_details->id is primary key of leave_authorities table ///////////////////
				$html .='<tr><td><input type="checkbox" name="leave_approve_req_forward[]" id="leave_approve_req_forward_'.$user_details->id.'" value="'.$user_details->user_id.'"/> </td>';
					$html .='<td>'.$user_details->name.'</td>';
					$html .='<td>'.$user_details->email.'</td>';
					$html .='<td>'.$user_details->mobile.' </td>'; 
				$html .='</tr>';
			
			}
		}else{
			$html .='<tr><td>No User found</td></tr>';
		}
        
        return response()->json(['status'=>200,'data' => $html]);        
    }
	
	
	public function SendForwardRequest(Request $request){
		
		$forward_data = [];
		
		
		$forwarded_by_user_id = Auth::user()->id;
		$forward_data['forwarded_by_name']=Auth::user()->name;
		$forward_data['forwarded_by_email']=Auth::user()->email;

		$forward_to_user_id_array  = $request->user_ids;
		
		$leave_id= $request->leave_id; 
		
		////////////// update Leave table for forwarded user ////////////////////
		
		$data = DB::select("SELECT a.user_id, a.forwarded_by, b.name, b.email, b.mobile FROM `leaves` as a INNER JOIN users as b on a.user_id=b.id WHERE a.id=$leave_id");
		//echo "<pre>";print_r($data);echo "</pre>";
		$message = "Approval request not send"; 
		
		$forward_data['leave_applied_user_name'] = $data[0]->name;
		
		foreach($forward_to_user_id_array as $user_array_id){
			
			
			$user_details = DB::select("SELECT name,email FROM users WHERE id=$user_array_id");
		
			
			$forward_data['forwarded_to_name'] = $user_details[0]->name;
			$forward_data['forwarded_to_email'] = $user_details[0]->email;
			$this->SendForwardMail($forward_data);
		}
		
		if(!empty($data[0])){
			$forwarded_by = $data[0]->forwarded_by;
			if(!empty($forwarded_by)){
				$forwarded_by_total_users = $forwarded_by .', '.$forwarded_by_user_id;
			}else{
				$forwarded_by_total_users = $forwarded_by_user_id;
			}
			
			$update_leave_table = DB::update("UPDATE `leaves` set forwarded_by = '".$forwarded_by_total_users."', updated_by = '".$forwarded_by_user_id."' where id =$leave_id");			
			
			if($update_leave_table){
				$message = "Approval request send successfully";
			}			
		}
	
		return response()->json(['status'=>200, 'message' => $message]);        
    }
	
	public function WithdrawLeaves(Request $request){

		$withdraw_by_user_id = Auth::user()->id;	
		$leave_id= $request->leave_id; 
		
		$today_date = date('Y-m-d');
		
		////////////// Update Leave table for withdraw Leaves ////////////////////
		
		$data = DB::select("SELECT a.user_id, a.forwarded_by, a.office_id, a.department_id, a.start_date, a.end_date, a.duration,a.withdraw_status, a.time_duration, b.name, 
		b.email, b.mobile, c.name as leave_type_name, c.orgnization_id,c.id as leave_type_id FROM `leaves` as a 
		INNER JOIN users as b on a.user_id=b.id
		INNER JOIN `leave_types` as c on c.id=a.leave_type WHERE a.id=$leave_id");
		
		//echo "<pre>";print_r($data);echo "</pre>";
		
		if(!empty($data[0])){
			$leave_start_date = $data[0]->start_date;
			$leave_end_date = $data[0]->end_date;
			$leave_duration = $data[0]->duration;
			
			$new_leave_end_date = date("Y-m-d", strtotime("yesterday")); 
			
			$user_mail_array = array();
			$user_mail_array['leave_start_date'] = $data[0]->start_date;
			$user_mail_array['leave_end_date'] = $data[0]->end_date;
			$user_mail_array['leave_applied_user_name'] = $data[0]->name;
			$user_mail_array['leave_type'] = $data[0]->leave_type_name;
			
			
			$orgnization_id= $data[0]->orgnization_id;
			$office_id= $data[0]->office_id;
			$department_id= $data[0]->department_id;
			$leave_type_id= $data[0]->leave_type_id;			
			
			
			if($leave_end_date > $new_leave_end_date){
				
				$date1=date_create($new_leave_end_date);
				$date2=date_create($leave_end_date);
				$diff=date_diff($date1,$date2);
				
				$leave_remaining_days_count = $diff->days;				
				
				$total_leave_take_by_user = $leave_duration - $leave_remaining_days_count;			
				//$sql = " , end_date='".$new_leave_end_date."' ";
				
			}			
			
			if($leave_start_date > $new_leave_end_date){
				$total_leave_take_by_user = 0;
				//$sql = " , start_date='".$new_leave_end_date."', end_date='".$new_leave_end_date."' ";
			}
			
			$get_flow_data = DB::select("SELECT flow_id FROM `approval_flows` WHERE orgnization_id=$orgnization_id AND office_id='".$office_id."' AND department_id='".$department_id."' AND leave_type=$leave_type_id order by id DESC");
			
			//echo "<pre>";print_r($get_flow_data);echo "</pre>";
			
			if(!empty($get_flow_data[0])){
				
				$flow_id= $get_flow_data[0]->flow_id; 
				$data[0]->flow_id= $flow_id;
				
				////////////// Get Approval authority count for show forward button ////////////////////
				$data_approval = DB::select("SELECT * FROM `leave_authorities` as a INNER JOIN users as b on a.user_id=b.id WHERE a.flow_id = $flow_id AND a.orgnization_id=$orgnization_id AND a.office_id=$office_id");
				//echo "<pre>";print_r($data_approval);echo "</pre>";		
				
				if(!empty($data_approval[0])){
					$user_mail_array['authority_user_name'] = $data_approval[0]->name;
					$user_mail_array['authority_user_email'] = $data_approval[0]->email;
					
					$approval_count = count($data_approval);				
				}else{
					$send_status = "There is no authority for this leave";
				}
			}
		
			//$update_leave_sql = "UPDATE `leaves` set withdraw_status='1', duration = '".$total_leave_take_by_user."' $sql  where id=$leave_id";
			$update_leave_sql = "UPDATE `leaves` set withdraw_status='1', updated_by=$withdraw_by_user_id, duration = '".$total_leave_take_by_user."' where id=$leave_id";
			
			$update_leave_table = DB::update($update_leave_sql);			
			
			if($update_leave_table){
				
				$this->sendWithdrawMail($user_mail_array);
				$message = "Leave withdraw successfully";
			}
		}
	
		return response()->json(['status'=>200, 'message' => $message]);        
    }
	
	
	public function sendWithdrawMail($data){
		//echo "<pre>";print_r($data);echo "</pre>";
        try {
            
            $template_data = [
                
                'leave_type' => $data['leave_type'],
                'leave_applied_user_name' => $data['leave_applied_user_name'],
                'leave_start_date' => $data['leave_start_date'],
                'leave_end_date' => $data['leave_end_date'],
				'authority_user_name' => $data['authority_user_name'],
                'forwarded_to_email' => $data['authority_user_email']
            ];
            Mail::send(['html'=>'email.withdraw_leave'], $template_data,
                function ($message) use ($data) {
                   $message->to($data['authority_user_email'])->from('lnxxapp@gmail.com')->subject('Leave withdraw');
                   //$message->to('ankit.tiwari2093@gmail.com')->from('lnxxapp@gmail.com')->subject('Leave withdraw' );
            });
            return true;
        } catch (Exception $ex) {
            return false;
        }  
    }
	
	public function deductCasualLeaveForShortLeave(Request $request){
		
		$office_id= $request->office_id; 
		$user_id= $request->user_id; 
		$organization_id= $request->organization_id; 
		
		$today_date = date('Y-m-d');
		
		////////////// Update Leave table for withdraw Leaves ////////////////////
		//echo "SELECT * from leave_types where name LIKE 'casual leave%' AND orgnization_id=$organization_id AND office_id=$office_id";
		//$data = DB::select("SELECT * from leave_types WHERE a.id=$leave_id");
		//$getCasualLeaveData = DB::select("SELECT * from leave_types where name LIKE 'casual leave' AND orgnization_id=$organization_id AND office_id=$office_id");
		
		$getCasualLeaveData = LeaveType::select('id','name','total_leave')
				->where('orgnization_id',$organization_id)
				->where('name', 'LIKE', 'casual leave')
				->where('office_id',$office_id)
				->first();	
		 
		$leave_type_id = $getCasualLeaveData->id;		
		
		//$get_remaining_short_leaves = DB::select("SELECT id, total_leaves from remaining_leaves WHERE leave_type_id=$leave_type_id AND user_id=$user_id AND office_id=$office_id AND organization_id=$organization_id");
		$get_remaining_short_leaves = DB::table('remaining_leaves')
										->where('leave_type_id', $leave_type_id) 
										->where('user_id', $user_id) 
										->where('office_id', $office_id)
										->where('organization_id', $organization_id)
										->where('total_leaves', '>', 0)
										->get(['id','total_leaves']);
										
		$total_short_leave = $get_remaining_short_leaves[0]->total_leaves;
		$updated_short_leave = $get_remaining_short_leaves[0]->total_leaves - 1;
		$short_leave_record_id = $get_remaining_short_leaves[0]->id;
		
		if(count($get_remaining_short_leaves) > 0){			
			$get_remaining_short_leaves = DB::update("UPDATE remaining_leaves set total_leaves=$updated_short_leave WHERE id=$short_leave_record_id");
			//$update_remaining_short_leaves = DB::insert("INSERT INTO short_leaves_coverted_to_casual_leave (user_id, office_id, organization_id, leave_type_id, short_leave_ids, deducation_month) VALUES($user_id, $office_id, $organization_id, $leave_type_id, ) remaining_leaves set total_leaves=$updated_short_leave WHERE id=$short_leave_record_id");
			if($get_remaining_short_leaves){
				$resp = ['status'=>200, 'message' => "Casual Leave deducted"];
			}else{
				$resp = ['status'=>200, 'message' => "No casual Leave deducted"];
			}
			
		}else{
			$resp = ['status'=>200, 'message' => "No casual Leave in employee account"];
		}
		
	
		return response()->json($resp);        
    }
	
	public function checkForAuthorityLeave(Request $request){
		
		$organisation_id = Auth::user()->id;
		
		if($request->type=='user'){
			
			//$user_id = Auth::user()->organisation_id;
			$data = DB::select("SELECT a.id, a.user_id, a.office_id, a.department_id,b.id as leave_type_id, b.orgnization_id, b.name as leave_name, c.name,c.mobile, es.employee_code, es.position_id FROM `leaves` as a INNER JOIN leave_types as b on a.leave_type=b.id INNER JOIN users as c on c.id=a.user_id INNER JOIN employee_infos as es on es.user_id=a.user_id WHERE a.id=$request->id ");
			
			if(!empty($data[0])){
				
				$leave_user_id = $data[0]->user_id; 
				$user_name = $data[0]->name; 
				
				if($leave_user_id == $organisation_id){
					$result = array('result'=> true, 'message'=> 'Employee "'.$user_name.'" is leave authority, So before approve leave please transfer authority rights to other employee.', 'flow_id'=> "");
				}else{
					$result = array('result'=> false, 'flow_id'=> '');
				}
				
			}else{
				$result = array('result'=> false, 'flow_id'=> '');
			}
		}else{
			
			$organisation_id = Auth::user()->id;
			$data = DB::select("SELECT a.id, a.user_id, a.office_id, a.department_id,b.id as leave_type_id, b.orgnization_id, b.name as leave_name, c.name,c.mobile, es.employee_code, es.position_id FROM `leaves` as a INNER JOIN leave_types as b on a.leave_type=b.id INNER JOIN users as c on c.id=a.user_id INNER JOIN employee_infos as es on es.user_id=a.user_id WHERE a.id=$request->id ");
		
			if(!empty($data[0])){
				
				$user_name = $data[0]->name; 
				$leave_user_id = $data[0]->user_id; 
				$officeId = $data[0]->office_id; 
				$department_id = $data[0]->department_id;
				$orgnization_id = $data[0]->orgnization_id;
				$leave_type_id = $data[0]->leave_type_id;
				
				$get_flow_data = DB::select("SELECT flow_id FROM `approval_flows` WHERE orgnization_id=$orgnization_id AND office_id='".$officeId."' AND department_id='".$department_id."' AND leave_type=$leave_type_id order by id DESC");
				
				if(!empty($get_flow_data[0])){
				
					$flow_id= $get_flow_data[0]->flow_id;				
					
					////////////// Get Approval authority count for transfer user rights ////////////////////
					
					$data_approval = DB::select("SELECT * FROM `leave_authorities` WHERE flow_id = $flow_id AND office_id=$officeId AND user_id=$leave_user_id");
					
					if(count($data_approval) > 0){			
						$result = array('result'=> true, 'message'=> 'Employee "'.$user_name.'" is leave authority, So before approve leave please transfer authority rights to other employee.', 'flow_id'=> $flow_id);
					}else{
						$result = array('result'=> false, 'flow_id'=> '');
					}
				}else{
					$result = array('result'=> false, 'flow_id'=> '');
				}
			}else{
				$result = array('result'=> false, 'flow_id'=> '');
			}
		}
        
        return response()->json(['status'=>200,'data' => $result]);        
    }
	
	
    public function GetLeaveMasters(){
        $user_id = Auth::user()->id;
        $data = DB::select("SELECT a.total_leave,a.name AS leaveName,a.id,b.office_name,c.department_name,d.emp_type FROM `leave_types` AS a INNER JOIN office_masters AS b ON b.id=a.office_id INNER JOIN department_masters AS c ON c.id=a.department_id INNER JOIN emp_types AS d ON d.id=a.emp_type WHERE a.orgnization_id=$user_id ORDER BY a.id DESC");
        return response()->json(['status'=>200,'data' => $data]);
    }
    public function GetListLeave(){
        $user_id = Auth::user()->id;
        $data = DB::select("SELECT a.id,a.user_id,a.duration,a.status,a.created_at,a.start_date,a.end_date,a.reason_for_leav_comp,b.name,c.name AS leave_type From leaves AS a INNER JOIN users AS b ON a.user_id=b.id INNER JOIN leave_types AS c on c.id=a.leave_type");
        return response()->json(['status'=>200,'data' => $data]);
    }
    
    public function GetParentDepartment($id){
        $user_id = Auth::user()->id;
        $select = DepartmentMaster::where('orgnization_id',$user_id)->where('office_id',$id)->where('parent_id',0)->where('type_of_department','>',0)->count();
        if($select!=0){
            $data = DepartmentMaster::select('id','department_name')->where('orgnization_id',$user_id)->where('office_id',$id)->where('type_of_department',0)->get();
        }else{
            $data = DepartmentMaster::select('id','department_name')->where('orgnization_id',$user_id)->where('office_id',$id)->get();
            
        }
        if(!empty($data)){
            return response()->json(['status'=>200,'data' => $data]);
        }else{
            return response()->json(['status'=>400,'data' => []]);
        }
    }
    public function GetParentPosition($ofice_id,$department_id){
        $user_id = Auth::user()->id;
        $select = PositionMaster::where('orgnization_id',$user_id)->where('office_id',$ofice_id)->where('department_id',$department_id)->where('parent_id',0)->where('type_of_position','>',0)->count();
        if($select!=0){
            $data = PositionMaster::select('id','position_name')->where('orgnization_id',$user_id)->where('office_id',$ofice_id)->where('department_id',$department_id)->where('type_of_position',0)->get();
        }else{
            $data = PositionMaster::select('id','position_name')->where('orgnization_id',$user_id)->where('office_id',$ofice_id)->where('department_id',$department_id)->get();
        }
        if(!empty($data)){
            return response()->json(['status'=>200,'data' => $data]);
        }else{
            return response()->json(['status'=>400,'data' => []]);
        }
    }
    // public function GetParentDepartment($id){
    //     $user_id = Auth::user()->id;
    //     $select = DepartmentMaster::where('orgnization_id',$user_id)->where('office_id',$id)->count();
    //     if($select>1){
    //         $data = DepartmentMaster::where('orgnization_id',$user_id)->where('office_id',$id)->where('department_id','!=',0)->get();
    //     }else{
    //         $data = DepartmentMaster::where('orgnization_id',$user_id)->where('office_id',$id)->get();
    //     }
    //     if(!empty($data)){
    //         return response()->json(['status'=>200,'data' => $data]);
    //     }else{
    //         return response()->json(['status'=>400,'data' => []]);
    //     }
    // }
    public function GetStatusDepartment(Request $request){
        $user_id = Auth::user()->id;
        $data = DepartmentMaster::select('id','status')->where('orgnization_id',$user_id)->where('id',$request->id)->first();
        $data->status = $request->status;
        $data->save();
        if(!empty($data)){
            return response()->json(['status'=>200,'data'=>$data]);
        }else{
            return response()->json(['status'=>400,'data'=>'']);
        }
    }
    public function GetStatusOffice(Request $request){
        $user_id = Auth::user()->id;
        $data = OfficeMaster::select('id','status')->where('orgnization_id',$user_id)->where('id',$request->id)->first();
        $data->status = $request->status;
        $data->save();
        if(!empty($data)){
            return response()->json(['status'=>200,'data'=>$data]);
        }else{
            return response()->json(['status'=>400,'data'=>'']);
        }
    }
    public function GetStatusPosition(Request $request){
        $user_id = Auth::user()->id;
        $data = PositionMaster::select('id','status')->where('orgnization_id',$user_id)->where('id',$request->id)->first();
        $data->status = $request->status;
        $data->save();
        if(!empty($data)){
            return response()->json(['status'=>200,'data'=>$data]);
        }else{
            return response()->json(['status'=>400,'data'=>'']);
        }
    }
    public function GetStatusNotice(Request $request){
        $user_id = Auth::user()->id;
        $data = NoticeMaster::select('id','status')->where('orgnization_id',$user_id)->where('id',$request->id)->first();
        $data->status = $request->status;
        $data->save();
        if(!empty($data)){
            return response()->json(['status'=>200,'data'=>$data]);
        }else{
            return response()->json(['status'=>400,'data'=>'']);
        }
    }
    public function GetDefaultNotice(Request $request){
        $user_id = Auth::user()->id;
        $data = NoticeMaster::select('id','is_default')->where('orgnization_id',$user_id)->where('id',$request->id)->first();
        $data->is_default = $request->is_default;
        $data->save();
        if(!empty($data)){
            return response()->json(['status'=>200,'data'=>$data]);
        }else{
            return response()->json(['status'=>400,'data'=>'']);
        }
    }
    public function GetDefaultEducation(Request $request){
        $user_id = Auth::user()->id;
        $data = EducationMaster::select('id','is_default')->where('orgnization_id',$user_id)->where('id',$request->id)->first();
        $data->is_default = $request->is_default;
        $data->save();
        if(!empty($data)){
            return response()->json(['status'=>200,'data'=>$data]);
        }else{
            return response()->json(['status'=>400,'data'=>'']);
        }
    }
    public function GetStatusForm(Request $request){
        $user_id = Auth::user()->id;
        $data = FormEngineCategory::select('id','is_multiple')->where('orgnization_id',$user_id)->where('id',$request->id)->first();
        $data->is_multiple = $request->is_multiple;
        $data->save();
        if(!empty($data)){
            return response()->json(['status'=>200,'data'=>$data]);
        }else{
            return response()->json(['status'=>400,'data'=>'']);
        }
    }
    public function GetStatusEducation(Request $request){
        $user_id = Auth::user()->id;
        $data = EducationMaster::select('id','status')->where('orgnization_id',$user_id)->where('id',$request->id)->first();
        $data->status = $request->status;
        $data->save();
        if(!empty($data)){
            return response()->json(['status'=>200,'data'=>$data]);
        }else{
            return response()->json(['status'=>400,'data'=>'']);
        }
    }
    public function ViewDepartmentData(Request $request){
        $data = DB::select("SELECT a.id,a.department_name,a.status,a.created_at,a.updated_at,b.office_name FROM `department_masters` AS a INNER JOIN office_masters AS b ON a.office_id=b.id where a.id=$request->id");
        if(!empty($data[0])){
            return response()->json(['status'=>200,'data' => $data[0]]);
        }else{
            return response()->json(['status'=>400,'data' => []]);
        }
    }
    public function ViewOfficeData(Request $request){

        $data = DB::select("SELECT a.status,a.pincode,a.office_name,a.address,c.name AS countryName,d.name AS stateName,e.name AS cityName FROM office_masters AS a INNER JOIN countries AS c ON a.country_id=c.id INNER JOIN states AS d ON a.state_id=d.id INNER JOIN cities AS e ON a.city_id=e.id where a.id=$request->id");

        if(!empty($data[0])){
            return response()->json(['status'=>200,'data' => $data[0]]);
        }else{
            return response()->json(['status'=>400,'data' => []]);
        }
    }
    public function ViewJobDetails(Request $request){
        $user_id = Auth::user()->id;
        $data = DB::select("SELECT a.id,a.job_title,a.no_of_vacancy,a.minimum_salary,a.maximum_salary,a.job_type,a.description,b.office_name,c.department_name,d.position_name,b.address FROM `resource_requirements` AS a INNER JOIN office_masters AS b ON a.office_id=b.id INNER JOIN department_masters AS c ON a.department_id=c.id INNER JOIN position_masters AS d ON a.position_id=d.id WHERE a.orgnization_id=$user_id and a.id=$request->id");

        if(!empty($data[0])){
            return response()->json(['status'=>200,'data' => $data[0]]);
        }else{
            return response()->json(['status'=>400,'data' => []]);
        }
    }
    public function GetJobTitle(Request $request){
        $user_id = Auth::user()->id;
        $data = ResourceRequirement::select(['id','job_title'])->where(['orgnization_id'=>$user_id])->orderBy('job_title', 'ASC')->get();
        return response()->json(['data' => $data]);
    }
    public function FetchRequirementDetails(Request $request){
        $user_id = Auth::user()->id;
        $query ='';
        if(!empty($request->job_title)){
            $query .="AND a.job_title='$request->job_title'";
        }
        if(!empty($request->minimum_salary)){
            $query .="AND a.minimum_salary>='$request->minimum_salary'";
        }
        if(!empty($request->maximum_salary)){
            $query .="AND a.maximum_salary<='$request->maximum_salary'";
        }
        $count = DB::select("SELECT COUNT(a.id) as id FROM `resource_requirements` AS a INNER JOIN office_masters AS b ON a.office_id=b.id INNER JOIN department_masters AS c ON a.department_id=c.id INNER JOIN position_masters AS d ON a.position_id=d.id WHERE a.orgnization_id=$user_id")[0];
        $requirement = DB::select("SELECT a.id,a.job_title,a.no_of_vacancy,a.minimum_salary,a.maximum_salary,a.job_type,a.description,b.office_name,c.department_name,d.position_name,b.address FROM `resource_requirements` AS a INNER JOIN office_masters AS b ON a.office_id=b.id INNER JOIN department_masters AS c ON a.department_id=c.id INNER JOIN position_masters AS d ON a.position_id=d.id WHERE a.orgnization_id=$user_id $query ORDER by a.id DESC limit 5 OFFSET $request->offset");
        return response()->json(['data' => $requirement,'count'=>round($count->id/2)]);
    }
    public function GetDepartmentName(Request $request){
        $user_id = Auth::user()->id;
        $departmentId=$request->department_id;
        if($departmentId=='0'){
            $data = DepartmentMaster::select('id','department_name')->where('orgnization_id',$user_id)->get();
            //echo "<pre>"; print_r($data); echo "</pre>"; die;
        }
        else {
        $data = DepartmentMaster::select('id','department_name')->where('office_id',$departmentId)->where('orgnization_id',$user_id)->get();
             //echo "<pre>"; print_r($data); echo "</pre>"; 
        }

        if(!empty($data)){
            return response()->json(['status'=>200,'data' => $data]);
        }else{
            return response()->json(['status'=>400,'data' => []]);
        }
    }


    public function GetDesignation(Request $request){ 
        $officeId=$request->office_id;
        $departmentId=$request->department_id;
        if($officeId=='0' and $departmentId =='0'){
        $data = PositionMaster::select('id','position_name')->get();
        }
        else {
        $data = PositionMaster::select('id','position_name')->where('office_id',$officeId)->where('department_id',$departmentId)->get();
        }

        //echo "<pre>"; print_r($data); echo "</pre>"; die;

        if(!empty($data)){
            return response()->json(['status'=>200,'data' => $data]);
        }else{
            return response()->json(['status'=>400,'data' => []]);
        }
    }
    public function searchEmpName()
    {
        if(!empty($_POST['search'])){
            $search_name=$_POST['search'];
            $result=DB::select("SELECT id,name FROM `users` WHERE type='2' AND name like '%$search_name%' ORDER BY name ASC");
            if(!empty($result)){
                foreach($result as $row){
                    $datas[]=$row;
                }
                echo json_encode($datas);
            }
        }
    }
    public function GetEmpTypeMaster(Request $request){ 
        $user_id = Auth::user()->id;
        //$data = EmpType::select('id','emp_type','created_at','updated_at')->where('orgnization_id',$user_id)->get();a
        $data = DB::select("SELECT a.id,a.emp_type,b.office_name,a.created_at,a.updated_at FROM `emp_types` as a INNER JOIN `office_masters` as b on a.office_id=b.id WHERE a.orgnization_id=$user_id");
        if(!empty($data)){
            return response()->json(['status'=>200,'data' => $data]);
        }else{
            return response()->json(['status'=>400,'data' => []]);
        }


    }
    public function GetStatusSourceMasters(Request $request){
        $user_id = Auth::user()->id;
        $data = SourceMaster::select('id','status')->where('orgnization_id',$user_id)->where('id',$request->id)->first();
        $data->status = $request->status;
        $data->save();
        if(!empty($data)){
            return response()->json(['status'=>200,'data'=>$data]);
        }else{
            return response()->json(['status'=>400,'data'=>'']);
        }
    }
    public function GetStatusProject(Request $request){
        $user_id = Auth::user()->id;
        $data = ProjectMaster::select('id','status')->where('orgnization_id',$user_id)->where('id',$request->id)->first();
        $data->status = $request->status;
        $data->save();
        if(!empty($data)){
            return response()->json(['status'=>200,'data'=>$data]);
        }else{
            return response()->json(['status'=>400,'data'=>'']);
        }
    }
    public function GetAssignTask(Request $request){
        $user_id = Auth::user()->id;

        $data = DB::select("SELECT a.id,a.status,a.message,b.project_name,b.start_date,b.end_date,c.activity_name FROM `assign_tasks` AS a INNER JOIN project_masters AS b ON a.project_id=b.id INNER JOIN project_activities AS c ON a.activity_id=c.id WHERE a.orgnization_id=$user_id");
       

        // $data = DB::select("SELECT a.id,a.status,a.message,c.project_name,c.start_date,c.end_date,GROUP_CONCAT(b.activity_name ORDER BY b.id) activity_name FROM assign_tasks a INNER JOIN project_activities b ON FIND_IN_SET(b.id, a.activity_id) > 0 INNER JOIN project_masters c ON FIND_IN_SET(c.id, a.project_id) > 0 WHERE a.orgnization_id=$user_id");

        if(!empty($data)){
            return response()->json(['status'=>200,'data'=>$data]);
        }else{
            return response()->json(['status'=>400,'data'=>'']);
        }
    }
    public function GetTaskByDepartment(Request $request){
        $user_id = Auth::user()->id;
        $data = ProjectMaster::select(['id','project_name'])->where(['office_id'=>$request->office_id])->where(['department_id'=>$request->department_id])->get();
        if(!empty($data)){
            return response()->json(['status'=>200,'data' => $data]);
        }else{
            return response()->json(['status'=>400,'data' => []]);
        }
    }
    public function GetAssignActivity(Request $request){
        $data = ProjectActivity::select(['id','activity_name'])->where(['project_id'=>$request->project_id])->orderBy('activity_name', 'ASC')->get();
        return response()->json(['data' => $data]);
    }
    public function GetShiftType(Request $request)
    {
        $select = WeekDay::get();
        if($request->type=='Daily'){ ?>
            <div class="col-sm-12 mt-4">
                <div class="form-group">
                    <h5 class="shift-ty header_change">Daily Shift Details, Duration: 9.0 Hrs, Break Duration: 0.0 Min</h5>
                </div>
            </div>
            <div class="col-md-4">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>Type of Shift*</label>
                            <div class="d-flex">
                                <label class="w-50"><input type="radio" name="type_of_shift1[]" class="mx-1" value="Day Shift"> Day Shift</label>
                                <label class="w-50"><input type="radio" class="mx-1" name="type_of_shift1[]" value="Night Shift"> Night Shift</label>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3"></div>
                    <p class="alert alert-info"><strong style="font-size: 15px;">*Note :</strong> Night Shift will include 12:00 AM in b/w the in time and out time</p>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>Continuous Double Shift</label>
                            <div class="form-check"><label class="switch">
                                <input name="continuous_double_shift[]" type="checkbox" value="1" class="continuous_double_shift"><span class="slider round"></span></label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Variable Shift</label>
                            <div class="form-check"><label class="switch">
                                <input name="variable_shift[]" value="1" type="checkbox" class="variable_shift"><span class="slider round"></span></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>In Time*</label>
                            <input type="time" class="form-control in_time" name="in_time" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Out Time*</label>
                            <input type="time" class="form-control out_time" name="out_time[]" required>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Break Start Time*</label>
                            <input type="time" class="form-control break_start_time" name="break_start_time[]" required>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Break End Time*</label>
                            <input type="time" class="form-control break_end_time" name="break_end_time[]" required>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>In Time Relaxation*</label>
                            <input type="time" class="form-control in_time_relaxation" name="in_time_relaxation[]" required>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Out Time Relaxation*</label>
                            <input type="time" class="form-control out_time_relaxation"" name="out_time_relaxation[]" required>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Minimum Present Duration (min)*</label>
                            <input type="number" class="form-control minimum_pres_dur" name="min_present_duration[]" onkeyup="CheckMinimumPresent(this.value)" required>
                            <label class="text-info minimum-half-time-duration" style="display:none"><strong>Minimum Half Time Duration (min)* <span class="text-primary mx-4 half-time-duration"> 0</span></strong></label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Enable Half Day</label>
                            <div class="form-check"><label class="switch">
                                <input name="enable_half_day[]" class="enable_half_day" onchange="HalfDayEnabled()" value="1" type="checkbox"><span class="slider round"></span></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php }elseif($request->type=='Flexible'){ ?>
            <div class="col-sm-12 mt-4">
                <div class="form-group">
                    <h5 class="shift-ty header_change">Flexible Shift Details</h5>
                </div>
            </div>
            <div class="col-md-4">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>Type of Shift*</label>
                            <div class="d-flex">
                                <label class="w-50"><input type="radio" name="type_of_shift1" class="mx-1" onclick="flexible_shift(1)" value="Day Shift"> Day Shift</label>
                                <label class="w-50"><input type="radio" class="mx-1" onclick="flexible_shift(2)" name="type_of_shift1" value="Night Shift"> Night Shift</label>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3"></div>
                    <p class="alert alert-info"><strong style="font-size: 15px;">*Note :</strong> Night Shift will include 12:00 AM in b/w the in time and out time</p>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>Continuous Double Shift</label>
                            <div class="form-check"><label class="switch">
                                <input name="continuous_double_shift" type="checkbox" value="1" class="continuous_double_shift"><span class="slider round"></span></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Shift Duration (min)*</label>
                            <input type="number" class="form-control shift_duration" placeholder="Enter Shift Durations" name="shift_duration" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>End Time*</label>
                            <input type="time" class="form-control out_time" name="out_time" value="00:00" readonly>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Minimum Present Duration (min)*</label>
                            <input type="number" class="form-control minimum_pres_dur" name="min_present_duration" onkeyup="CheckMinimumPresent(this.value)" required>
                            <label class="text-info minimum-half-time-duration" style="display:none"><strong>Minimum Half Time Duration (min)* <span class="text-primary mx-4 half-time-duration"> 0</span></strong></label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Enable Half Day</label>
                            <div class="form-check"><label class="switch">
                                <input name="enable_half_day" class="enable_half_day" onchange="HalfDayEnabled()" value="1" type="checkbox"><span class="slider round"></span></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                function flexible_shift(num){
                    if(num==1){
                        $('.out_time').attr('readonly',true);
                    }else{
                        $('.out_time').attr('readonly',false);
                        $('.out_time').val();
                    }
                }
            </script>
        <?php }elseif($request->type=='Weekly'){
            if(!empty($select)){
                foreach($select as $row){ ?>
                    <div class="col-sm-12 mt-4">
                        <div class="form-group">
                            <h5 class="shift-ty header_change"><?=$row->name;?> Shift Details, Duration: 9.0 Hrs, Break Duration: 0.0 Min</h5>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Type of Shift*</label>
                                    <div class="d-flex">
                                        <label class="w-50"><input type="radio" name="type_of_shift<?=$row->id;?>[]" class="mx-1" value="Day Shift"> Day Shift</label>
                                        <label class="w-50"><input type="radio" class="mx-1" name="type_of_shift<?=$row->id;?>[]" value="Night Shift"> Night Shift</label>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3"></div>
                            <p class="alert alert-info"><strong style="font-size: 15px;">*Note :</strong> Night Shift will include 12:00 AM in b/w the in time and out time</p>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Continuous Double Shift</label>
                                    <div class="form-check"><label class="switch">
                                        <input name="continuous_double_shift[]" type="checkbox" value="1" class="continuous_double_shift"><span class="slider round"></span></label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Variable Shift</label>
                                    <div class="form-check"><label class="switch">
                                        <input name="variable_shift[]" value="1" type="checkbox" class="variable_shift"><span class="slider round"></span></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>In Time*</label>
                                    <input type="time" class="form-control in_time" name="in_time[]" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Out Time*</label>
                                    <input type="time" class="form-control out_time" name="out_time[]" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Break Start Time*</label>
                                    <input type="time" class="form-control break_start_time" name="break_start_time[]" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Break End Time*</label>
                                    <input type="time" class="form-control break_end_time" name="break_end_time[]" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>In Time Relaxation*</label>
                                    <input type="time" class="form-control in_time_relaxation" name="in_time_relaxation[]" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Out Time Relaxation*</label>
                                    <input type="time" class="form-control out_time_relaxation"" name="out_time_relaxation[]" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Minimum Present Duration (min)*</label>
                                    <input type="number" class="form-control minimum_pres_dur<?=$row->id;?>" name="min_present_duration[]" required>
                                    <label class="text-info minimum-half-time-duration<?=$row->id;?>" style="display:none"><strong>Minimum Half Time Duration (min)* <span class="text-primary mx-4 half-time-duration<?=$row->id;?>"> 0</span></strong></label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Enable Half Day</label>
                                    <div class="form-check"><label class="switch">
                                        <input name="enable_half_day[]" class="enable_half_day<?=$row->id;?>" type="checkbox"><span class="slider round"></span></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <script>
                        $(".minimum_pres_dur<?=$row->id;?>").keyup(function(e){
                            $('.half-time-duration<?=$row->id;?>').text($(".minimum_pres_dur<?=$row->id;?>").val());
                        });
                        $(".enable_half_day<?=$row->id;?>").change(function(e){
                            if ($('.enable_half_day<?=$row->id;?>').is(':checked')) {
                                $('.minimum-half-time-duration<?=$row->id;?>').show();
                            }else{
                                $('.minimum-half-time-duration<?=$row->id;?>').hide();
                            }
                        });
                    </script>
                <?php }
            }
        }
    }
    public function GetEmployeeByDepartment(Request $request){
        $user_id = Auth::user()->id;
        $data = DB::select("SELECT b.id,a.employee_code,b.name FROM `employee_infos` as a INNER JOIN users as b on a.user_id=b.id WHERE a.department_id=$request->department_id AND a.organisation_id=$user_id AND a.office_id=$request->office_id GROUP BY b.id");
        if(!empty($data)){
            return response()->json(['status'=>200,'data' => $data]);
        }else{
            return response()->json(['status'=>400,'data' => []]);
        }
    }
    public function GetLeaveType(Request $request){
        $user_id = Auth::user()->id;
        $date=date('Y-m-d');
        $data = LeaveType::select('id','name','total_leave')->where('orgnization_id',$user_id)->where('department_id',$request->department_id)->where('office_id',$request->office_id)->get();
        $allldatas=array();
        foreach($data as $rows){
            $select = DB::select("SELECT SUM(duration) as leave_type FROM `leaves` WHERE leave_type=$rows->id AND status='Approved' AND user_id=$request->user_id AND YEAR(created_at)='$date' LIMIT 1");
            if(!empty($select[0]->leave_type)){
                $rows->totalleave = $rows->total_leave - $select[0]->leave_type;
            }else{
                $rows->totalleave = $rows->total_leave;
            }
            $allldatas[] = $rows;
        }
        if(!empty($data)){
            return response()->json(['status'=>200,'data' => $allldatas]);
        }else{
            return response()->json(['status'=>400,'data' => []]);
        }
    }
    public function GetEmployeeByPosition(Request $request){
        $user_id = Auth::user()->id;
        $data = DB::select("SELECT b.id,b.name,a.employee_code FROM `employee_infos` as a INNER JOIN users as b on a.user_id=b.id WHERE employee_code IS NOT null AND a.organisation_id=$user_id AND a.office_id=$request->office_id AND a.department_id=$request->department_id AND a.position_id=$request->position_id");
        if(!empty($data)){
            return response()->json(['status'=>200,'data' => $data]);
        }else{
            return response()->json(['status'=>400,'data' => []]);
        }
    }
    public function GetLeave(Request $request){
        $user_id = Auth::user()->id;
        $data = DB::select("SELECT a.id,a.name,a.total_leave,b.emp_type FROM `leave_types` as a INNER JOIN emp_types as b on a.emp_type=b.id WHERE a.orgnization_id=$user_id AND a.office_id=$request->office_id AND a.department_id=$request->department_id ORDER BY a.name ASC");
        //LeaveType::select('id','name','total_leave')->where('orgnization_id',$user_id)->where('department_id',$request->department_id)->where('office_id',$request->office_id)->get();
        if(!empty($data)){
            return response()->json(['status'=>200,'data' => $data]);
        }else{
            return response()->json(['status'=>400,'data' => []]);
        }
    }


        public function GetFlowRecords(Request $request){ 
       // echo "<pre>"; print_r($request->all()); echo "</pre>"; die;
        $user_id = Auth::user()->id;
        $data = FlowMaster::where('id',$request->flow_id)->where('orgnization_id',$user_id)->first();
        if(!empty($data)){
            return response()->json(['status'=>200,'data' => $data]);
        }else{
            return response()->json(['status'=>400,'data' => []]);
        }
    } 





    
    public function GetLeaveFlow(){
        $user_id = Auth::user()->id;
        $data = DB::select("SELECT a.id,a.flow_name,b.office_name,c.department_name,d.position_name,e.emp_type,CONCAT(f.name,' - ',f.total_leave) as name,a.created_at FROM `approval_flows` as a INNER JOIN office_masters as b on a.office_id=b.id INNER JOIN department_masters as c on a.department_id=c.id INNER JOIN position_masters as d on a.position_id=d.id INNER JOIN leave_types as f on a.leave_type=f.id INNER JOIN emp_types as e on f.emp_type=e.id WHERE a.orgnization_id=$user_id ORDER BY a.id DESC");
        if(!empty($data)){
            return response()->json(['status'=>200,'data' => $data]);
        }else{
            return response()->json(['status'=>400,'data' => []]);
        }
    }

/*----==========START SAVE FLOW NAME=========-------*/
     public function SaveFlowName(Request $request){
        $user_id = Auth::user()->id;
        $select = FlowMaster::where('flow_name',$request->flow_name)->where('orgnization_id',$user_id)->first();
        if(!empty($select)){
            $approval = ApprovalFlow::select('office_id')->where('flow_id',$select->id)->first();
            if(!empty($approval)){
                $office = OfficeMaster::select('id','office_name')->where('id',$approval->office_id)->first();
            }else{
                $office=[];
            }

            $result = DB::select("SELECT a.id,a.flow_id,g.flow_name,b.office_name,c.department_name,d.position_name,e.emp_type,CONCAT(f.name,' - ',f.total_leave) as name,a.created_at FROM `approval_flows` as a INNER JOIN office_masters as b on a.office_id=b.id INNER JOIN department_masters as c on a.department_id=c.id INNER JOIN position_masters as d on a.position_id=d.id INNER JOIN leave_types as f on a.leave_type=f.id INNER JOIN emp_types as e on f.emp_type=e.id INNER JOIN flow_masters as g on a.flow_id=g.id WHERE a.orgnization_id=$user_id AND a.flow_id=$select->id ORDER BY a.id DESC");

            $datas = DB::select("SELECT a.id,a.flow_id,b.flow_name,c.office_name,d.department_name,e.position_name,f.name,a.created_at FROM `leave_authorities` as a INNER JOIN flow_masters as b on a.flow_id=b.id INNER JOIN office_masters as c on a.office_id=c.id INNER JOIN department_masters as d on a.department_id=d.id INNER JOIN position_masters as e on a.position_id=e.id INNER JOIN users as f ON a.user_id=f.id WHERE a.flow_id=$select->id AND a.orgnization_id=$user_id");
            $html='<div class="alert alert-info">
    <strong>SORRY!</strong> Already added this flow.<br><b>Try with different flow name.</b></div>';
            $msg="Duplicate Entry";

            //$html;
            return response()->json(['status'=>400,'msg'=>$msg,]);
        }else{
            $flow_master = new FlowMaster();
            $flow_master->flow_name = $request->flow_name;
            $flow_master->orgnization_id = $user_id;
            $flow_master->save();
            $approval = ApprovalFlow::select('office_id')->where('flow_id',$flow_master->id)->first();
            if(!empty($approval)){
                $office = OfficeMaster::select('id','office_name')->where('id',$approval->office_id)->first();
            }else{
                $office=[];
            }
            $result = DB::select("SELECT a.id,g.flow_name,b.office_name,c.department_name,d.position_name,e.emp_type,CONCAT(f.name,' - ',f.total_leave) as name,a.created_at FROM `approval_flows` as a INNER JOIN office_masters as b on a.office_id=b.id INNER JOIN department_masters as c on a.department_id=c.id INNER JOIN position_masters as d on a.position_id=d.id INNER JOIN leave_types as f on a.leave_type=f.id INNER JOIN emp_types as e on f.emp_type=e.id INNER JOIN flow_masters as g on a.flow_id=g.id WHERE a.orgnization_id=$user_id AND a.flow_id=$flow_master->id ORDER BY a.id DESC");
            $datas = DB::select("SELECT a.id,a.flow_id,b.flow_name,c.office_name,d.department_name,e.position_name,f.name,a.created_at FROM `leave_authorities` as a INNER JOIN flow_masters as b on a.flow_id=b.id INNER JOIN office_masters as c on a.office_id=c.id INNER JOIN department_masters as d on a.department_id=d.id INNER JOIN position_masters as e on a.position_id=e.id INNER JOIN users as f ON a.user_id=f.id WHERE a.flow_id=$flow_master->id AND a.orgnization_id=$user_id");

            /*echo "<pre>"; print_r($flow_master); echo "</pre>";
            echo "</br>";
            echo "<pre>"; print_r($result); die;*/
            return response()->json(['status'=>200,'msg'=>'Added successfully','flow'=>$flow_master,'datas'=>$result,'authorities'=>$datas,'office'=>$office]);
        }
    }
/*----==========END SAVE FLOW NAME=========-------*/

    public function SaveApprovalFlow(Request $request){

        $flow_id=$request->flow_id;
        //echo "<pre>"; print_r($request->all()); echo "</pre>"; die;
        $user_id = Auth::user()->id;
        $select = ApprovalFlow::select('id','office_id','flow_id')->where('flow_id',$request->flow_id)->where('office_id',$request->office_id)->where('department_id',$request->department_id)->where('position_id',$request->position_id)->where('leave_type',$request->leave_type)->first();

        if(empty($select->id)){
            $approval_flow = new ApprovalFlow();
            $approval_flow->orgnization_id = $user_id;
            $approval_flow->flow_id = $request->flow_id;
            $approval_flow->office_id = $request->office_id;
            $approval_flow->department_id = $request->department_id;
            $approval_flow->position_id = $request->position_id;
            $approval_flow->leave_type = $request->leave_type;
            $approval_flow->save();
            $office = OfficeMaster::select('id','office_name')->where('id',$request->office_id)->first();
            $result = DB::select("SELECT a.id,g.flow_name,g.id as flow_id,b.office_name,c.department_name,d.position_name,e.emp_type,CONCAT(f.name,' - ',f.total_leave) as name,a.created_at FROM `approval_flows` as a INNER JOIN office_masters as b on a.office_id=b.id INNER JOIN department_masters as c on a.department_id=c.id INNER JOIN position_masters as d on a.position_id=d.id INNER JOIN leave_types as f on a.leave_type=f.id INNER JOIN emp_types as e on f.emp_type=e.id INNER JOIN flow_masters as g on a.flow_id=g.id WHERE a.orgnization_id=$user_id AND g.id=$flow_id AND a.flow_id=$approval_flow->flow_id ORDER BY a.id DESC");

            return response()->json(['status'=>200,'msg'=>'Added Successfully','datas'=>$result,'office'=>$office]);
        }else{
            $office = OfficeMaster::select('id','office_name')->where('id',$select->office_id)->first();
            $result = DB::select("SELECT a.id,g.flow_name,b.office_name,c.department_name,d.position_name,e.emp_type,CONCAT(f.name,' - ',f.total_leave) as name,a.created_at FROM `approval_flows` as a INNER JOIN office_masters as b on a.office_id=b.id INNER JOIN department_masters as c on a.department_id=c.id INNER JOIN position_masters as d on a.position_id=d.id INNER JOIN leave_types as f on a.leave_type=f.id INNER JOIN emp_types as e on f.emp_type=e.id INNER JOIN flow_masters as g on a.flow_id=g.id WHERE a.orgnization_id=$user_id AND g.id=$flow_id AND a.flow_id=$select->flow_id");
               echo "<pre>"; print_r($result); echo "</pre>"; die;

                return response()->json(['status'=>400,'msg'=>'Already added this flow','datas'=>$result,'office'=>$office]);
        }
    }

    /*-------START NEW SAVE ALL APPROVAL FLOW------*/
      public function SaveAllApprovalFlow(Request $request){ 
        //echo "<pre>"; print_r($request->all()); echo "</pre>"; die;
        $user_id = Auth::user()->id;
        $select = ApprovalFlow::select('id','office_id')->where('flow_id',$request->flow_id)->where('office_id',$request->office_id)->first();
        if(empty($select->id)){
            $approval_flow = new ApprovalFlow();
            $approval_flow->orgnization_id = $user_id;
            $approval_flow->flow_id = $request->flow_id;
            $approval_flow->office_id = $request->office_id;
            $approval_flow->department_id = '0';
            $approval_flow->position_id = '0';
            $approval_flow->leave_type = '0';
            $approval_flow->save();
            $office = OfficeMaster::select('id','office_name')->where('id',$request->office_id)->first();
            $result = DB::select("SELECT a.id,g.flow_name,b.office_name,c.department_name,d.position_name,e.emp_type,CONCAT(f.name,' - ',f.total_leave) as name,a.created_at FROM `approval_flows` as a INNER JOIN office_masters as b on a.office_id=b.id INNER JOIN department_masters as c on a.department_id=c.id INNER JOIN position_masters as d on a.position_id=d.id INNER JOIN leave_types as f on a.leave_type=f.id INNER JOIN emp_types as e on f.emp_type=e.id INNER JOIN flow_masters as g on a.flow_id=g.id WHERE a.orgnization_id=$user_id AND a.flow_id=$approval_flow->flow_id ORDER BY a.id DESC");
            return response()->json(['status'=>200,'msg'=>'Added Successfully','datas'=>$result,'office'=>$office]);
        }else{
            $office = OfficeMaster::select('id','office_name')->where('id',$select->office_id)->first();
            $result = DB::select("SELECT a.id,g.flow_name,b.office_name,c.department_name,d.position_name,e.emp_type,CONCAT(f.name,' - ',f.total_leave) as name,a.created_at FROM `approval_flows` as a INNER JOIN office_masters as b on a.office_id=b.id INNER JOIN department_masters as c on a.department_id=c.id INNER JOIN position_masters as d on a.position_id=d.id INNER JOIN leave_types as f on a.leave_type=f.id INNER JOIN emp_types as e on f.emp_type=e.id INNER JOIN flow_masters as g on a.flow_id=g.id WHERE a.orgnization_id=$user_id AND a.flow_id=$select->flow_id ORDER BY a.id DESC");
                return response()->json(['status'=>400,'msg'=>'Already added this flow','datas'=>$result,'office'=>$office]);
        }
    }


    

    /*-------END NEW SAVE ALL APPROVAL FLOW------*/
 public function SaveAuthorityAdmin(Request $request){
        $user_id = Auth::user()->id;
        $office_id = $request->office_id;
        $flow_id = $request->flow_id;
       // echo "<pre>"; print_r($request->all()); echo "</pre>"; die;
        $select = LeaveAuthority::select('id')->where([
            'flow_id'=>$request->flow_id,
            'orgnization_id'=>$user_id,
            'office_id'=>$request->office_id,
            'department_id'=>$request->department_id,
            'position_id'=>$request->position_id,
            'user_id'=>$request->authority_user,
        ])->first();
        
        if($office_id=='0') {
            $records = DB::select("SELECT a.id,a.flow_id,b.flow_name,c.office_name,d.department_name,e.position_name,f.name,a.created_at FROM `leave_authorities` as a INNER JOIN flow_masters as b on a.flow_id=b.id INNER JOIN office_masters as c on a.office_id=c.id INNER JOIN department_masters as d on a.department_id=d.id INNER JOIN position_masters as e on a.position_id=e.id INNER JOIN users as f ON a.user_id=f.id WHERE a.flow_id=$request->flow_id  AND a.orgnization_id=$user_id");



        if(!empty($records)) {
            return response()->json(['status'=>400,'msg'=>'Already added this approval','datas'=>$records]);
            } else{

            $leave_authority = new LeaveAuthority();
            $leave_authority->flow_id = $request->flow_id;
            $leave_authority->orgnization_id = $user_id;
            $leave_authority->office_id = $request->office_id;
            $leave_authority->save();

            $datas = DB::select("SELECT a.id,a.flow_id,b.flow_name,c.office_name,d.department_name,e.position_name,f.name,a.created_at FROM `leave_authorities` as a INNER JOIN flow_masters as b on a.flow_id=b.id INNER JOIN office_masters as c on a.office_id=c.id INNER JOIN department_masters as d on a.department_id=d.id INNER JOIN position_masters as e on a.position_id=e.id INNER JOIN users as f ON a.user_id=f.id WHERE a.flow_id=$request->flow_id  AND a.orgnization_id=$user_id");
            return response()->json(['status'=>200,'msg'=>'Added Successfully','datas'=>$datas]);

            }

        }

        else if(empty($select)){
            $leave_authority = new LeaveAuthority();
            $leave_authority->flow_id = $request->flow_id;
            $leave_authority->orgnization_id = $user_id;
            $leave_authority->office_id = $request->office_id;
            $leave_authority->department_id = $request->department_id;
            $leave_authority->position_id = $request->position_id;
            $leave_authority->user_id = $request->authority_user;
            $leave_authority->save();
            $datas = DB::select("SELECT a.id,a.flow_id,b.flow_name,c.office_name,d.department_name,e.position_name,f.name,a.created_at FROM `leave_authorities` as a INNER JOIN flow_masters as b on a.flow_id=b.id INNER JOIN office_masters as c on a.office_id=c.id INNER JOIN department_masters as d on a.department_id=d.id INNER JOIN position_masters as e on a.position_id=e.id INNER JOIN users as f ON a.user_id=f.id WHERE a.flow_id=$request->flow_id AND a.orgnization_id=$user_id");
            return response()->json(['status'=>200,'msg'=>'Added Successfully','datas'=>$datas]);
             //return redirect('add-approval-flow')->with('success','Added Successfully');


        }else{
            return response()->json(['status'=>400,'msg'=>'Already added this approval','datas'=>[]]);
        }
    }

    public function SaveSettings(Request $request){
        $user_id = Auth::user()->id;

        //echo "<pre>"; print_r($request->all()); echo "</pre>"; die;

        $select = NotificationSetting::where('flow_id',$request->flow_id)->where('flow_type','leave-flow')->first();
        FlowMaster::where('id',$request->flow_id)->update(['is_complete'=>1]);
        if(!empty($select)){
            $select->flow_id = $request->flow_id;
            $select->flow_type = 'leave-flow';
            $select->orgnization_id = $user_id;
            $select->email_for_approve = !empty($request->email_for_approve) ? 1:0;
            $select->email_for_reject = !empty($request->email_for_reject) ? 1:0;
            $select->sms_for_approve = !empty($request->sms_for_approve) ? 1:0;
            $select->sms_for_reject = !empty($request->sms_for_reject) ? 1:0;
            $select->app_for_approve = !empty($request->app_for_approve) ? 1:0;
            $select->app_for_reject = !empty($request->app_for_reject) ? 1:0;
            $select->save();
            return response()->json(['status'=>200,'msg'=>'Updated setting','datas'=>$select]);
        }else{
            $setting  = new NotificationSetting();
            $setting->flow_id = $request->flow_id;
            $setting->flow_type = 'leave-flow';
            $setting->orgnization_id = $user_id;
            $setting->email_for_approve = !empty($request->email_for_approve) ? 1:0;
            $setting->email_for_reject = !empty($request->email_for_reject) ? 1:0;
            $setting->sms_for_approve = !empty($request->sms_for_approve) ? 1:0;
            $setting->sms_for_reject = !empty($request->sms_for_reject) ? 1:0;
            $setting->app_for_approve = !empty($request->app_for_approve) ? 1:0;
            $setting->app_for_reject = !empty($request->app_for_reject) ? 1:0;
            $setting->save();
            return response()->json(['status'=>200,'msg'=>'Saved setting','datas'=>$setting]);
        }
    }

   public function GetFlowData(Request $request){
        $user_id = Auth::user()->id;
        $data = FlowMaster::where('id',$request->id)->where('orgnization_id',$user_id)->first();
        if(!empty($data)){
            return response()->json(['status'=>200,'data' => $data]);
        }else{
            return response()->json(['status'=>400,'data' => []]);
        }
    } 



     public function GetRootFlowData(Request $request){
        $flow_id=$request->id;
        $user_id = Auth::user()->id;
        $data = FlowMaster::where('id',$request->id)->where('orgnization_id',$user_id)->get();
        
        if(!empty($data)){

        $leave_flow = DB::select("SELECT a.id,g.flow_name,g.id as flow_id,b.office_name,c.department_name,d.position_name,e.emp_type,CONCAT(f.name,' - ',f.total_leave) as name,a.created_at FROM `approval_flows` as a INNER JOIN office_masters as b on a.office_id=b.id INNER JOIN department_masters as c on a.department_id=c.id INNER JOIN position_masters as d on a.position_id=d.id INNER JOIN leave_types as f on a.leave_type=f.id INNER JOIN emp_types as e on f.emp_type=e.id INNER JOIN flow_masters as g on a.flow_id=g.id WHERE a.orgnization_id=$user_id AND g.id=$flow_id ORDER BY a.id ASC");
       // echo "<pre>"; print_r($leave_flow); echo "</pre>"; die;

        $authority_flow = DB::select("SELECT a.id,a.flow_id,b.flow_name,c.office_name,d.department_name,e.position_name,f.name,a.created_at FROM `leave_authorities` as a INNER JOIN flow_masters as b on a.flow_id=b.id INNER JOIN office_masters as c on a.office_id=c.id INNER JOIN department_masters as d on a.department_id=d.id INNER JOIN position_masters as e on a.position_id=e.id INNER JOIN users as f ON a.user_id=f.id AND b.id=$flow_id AND a.orgnization_id=$user_id ORDER BY a.id ASC");
       $selectNotification = NotificationSetting::where('flow_id',$flow_id)->where('orgnization_id',$user_id)->where('flow_type','leave-flow')->first(); 

       // echo "<pre>"; print_r($selectNotification); echo "</pre>";  

         if(!empty($selectNotification)){
            $email_for_approve=$selectNotification->email_for_approve;
            $email_for_reject=$selectNotification->email_for_reject;
            $sms_for_approve=$selectNotification->sms_for_approve;
            $sms_for_reject=$selectNotification->sms_for_reject;
            $app_for_approve=$selectNotification->app_for_approve;
            $app_for_reject=$selectNotification->app_for_reject;

            if($email_for_approve=='1'){
                $email_approve='Yes';
            }else{
                $email_approve='No';
            }

            if($email_for_reject=='1'){
                $email_reject='Yes';
            }else{
                $email_reject='No';
            }
            /*-----SMS-------*/

            if($sms_for_approve=='1'){
                $sms_approve='Yes';
            }else{
                $sms_approve='No';
            }

            if($sms_for_reject=='1'){
                $sms_reject='Yes';
            }else{
                $sms_reject='No';
            }
            /*------APP------*/

            if($app_for_approve=='1'){
                $app_approve='Yes';
            }else{
                $app_approve='No';
            }
            if($app_for_reject=='1'){
                $app_reject='Yes';
            }else{
                $app_reject='No';
            }

         }
         else{
            $email_approve='No';
            $email_reject='No';
            $sms_approve='No';
            $sms_reject='No';
            $app_approve='No';
            $app_reject='No';
         }
        $setting_notification=array(['email_for_approve'=>$email_approve,'email_for_reject'=>$email_reject,'sms_for_approve'=>$sms_approve,'sms_for_reject'=>$sms_reject,'app_for_approve'=>$app_approve,'app_for_reject'=>$app_reject]);


            return response()->json(['status'=>200,'data'=>$leave_flow, 'authority_flow'=>$authority_flow,'notification'=>$setting_notification]);
        }else{
            return response()->json(['status'=>400,'data' => []]);
        }

    } 




    
    public function GetLeaveFlowData(Request $request){
       // echo "<pre>"; print_r($request->all());  echo "<pre>"; die;
        $user_id = Auth::user()->id;
        $select = FlowMaster::where('id',$request->id)->where('orgnization_id',$user_id)->first();
        $result = DB::select("SELECT a.id,g.flow_name,b.office_name,c.department_name,d.position_name,e.emp_type,CONCAT(f.name,' - ',f.total_leave) as name,a.created_at FROM `approval_flows` as a INNER JOIN office_masters as b on a.office_id=b.id INNER JOIN department_masters as c on a.department_id=c.id INNER JOIN position_masters as d on a.position_id=d.id INNER JOIN leave_types as f on a.leave_type=f.id INNER JOIN emp_types as e on f.emp_type=e.id INNER JOIN flow_masters as g on a.flow_id=g.id WHERE a.orgnization_id=$user_id AND a.flow_id=$request->id ORDER BY a.id DESC");

       $datas = DB::select("SELECT a.id,b.flow_name,c.office_name,d.department_name,e.position_name,f.name,a.created_at FROM `leave_authorities` as a INNER JOIN flow_masters as b on a.flow_id=b.id INNER JOIN office_masters as c on a.office_id=c.id INNER JOIN department_masters as d on a.department_id=d.id INNER JOIN position_masters as e on a.position_id=e.id INNER JOIN users as f ON a.user_id=f.id WHERE a.flow_id=$request->id AND a.orgnization_id=$user_id");
       $selectNotification = NotificationSetting::where('flow_id',$request->id)->where('orgnization_id',$user_id)->where('flow_type','leave-flow')->first();

       //echo "<pre>"; print_r($selectNotification); echo "</pre>"; die;
         if(!empty($selectNotification)){
           $email_for_approve=$selectNotification->email_for_approve;
           $email_for_reject=$selectNotification->email_for_reject;
            $sms_for_approve=$selectNotification->sms_for_approve;
            $sms_for_reject=$selectNotification->sms_for_reject;

            $app_for_approve=$selectNotification->app_for_approve;
            $app_for_reject=$selectNotification->app_for_reject;

            if($email_for_approve=='1'){
                $email_approve='Yes';
            }else{
                $email_approve='No';
            }

            if($email_for_reject=='1'){
                $email_reject='Yes';
            }else{
                $email_reject='No';
            }
            /*-----SMS-------*/

            if($sms_for_approve=='1'){
                $sms_approve='Yes';
            }else{
                $sms_approve='No';
            }

            if($sms_for_reject=='1'){
                $sms_reject='Yes';
            }else{
                $sms_reject='No';
            }
            /*------APP------*/

            if($app_for_approve=='1'){
                $app_approve='Yes';
            }else{
                $app_approve='No';
            }
            if($app_for_reject=='1'){
                $app_reject='Yes';
            }else{
                $app_reject='No';
            }

         }
         else{
            $email_approve='No';
            $email_reject='No';
            $sms_approve='No';
            $sms_reject='No';
            $app_approve='No';
            $app_reject='No';
         }
        $setting_notofication=array(['email_for_approve'=>$email_approve,'email_for_reject'=>$email_reject,'sms_for_approve'=>$sms_approve,'sms_for_reject'=>$sms_reject,'app_for_approve'=>$app_approve,'app_for_reject'=>$app_reject]);
         //echo "<pre>"; print_r($setting_notofication);  echo "<pre>"; die;

        return response()->json(['status'=>200,'msg'=>'Succefully fetach','flow'=>$select,'datas'=>$result,'authorities'=>$datas, 'notification'=>$setting_notofication,]);
    }

    /*-----------START GET LEAVE APPROVAL FLOW DATA-----------------*/
     public function GetLeaveApprovalFlowData(Request $request){ 
        $user_id = Auth::user()->id;
        $select = FlowMaster::where('id',$request->id)->where('orgnization_id',$user_id)->first();
        $result = DB::select("SELECT a.id,g.flow_name,g.created_at,b.office_name,c.department_name,d.position_name,e.emp_type,CONCAT(f.name,' - ',f.total_leave) as name,a.created_at FROM `approval_flows` as a INNER JOIN office_masters as b on a.office_id=b.id INNER JOIN department_masters as c on a.department_id=c.id INNER JOIN position_masters as d on a.position_id=d.id INNER JOIN leave_types as f on a.leave_type=f.id INNER JOIN emp_types as e on f.emp_type=e.id INNER JOIN flow_masters as g on a.flow_id=g.id WHERE a.orgnization_id=$user_id AND a.flow_id=$request->id ORDER BY a.id DESC");

        $datas = DB::select("SELECT a.id,b.flow_name,c.office_name,d.department_name,e.position_name,f.name,a.created_at FROM `leave_authorities` as a INNER JOIN flow_masters as b on a.flow_id=b.id INNER JOIN office_masters as c on a.office_id=c.id INNER JOIN department_masters as d on a.department_id=d.id INNER JOIN position_masters as e on a.position_id=e.id INNER JOIN users as f ON a.user_id=f.id WHERE a.flow_id=$request->id AND a.orgnization_id=$user_id");

        return response()->json(['status'=>200,'msg'=>'Succefully fetach','flow'=>$select,'datas'=>$result,'authorities'=>$datas]);
    }
    /*-----------END GET LEAVE APPROVAL FLOW DATA-----------------*/


/*-----------START GET AUTHORITY APPROVAL FLOW DATA-----------------*/
      public function GetAuthorityApprovalFlowData(Request $request){ 
     $requestData=explode(',', $request->id);
       $flowid=$requestData[0];
       $ids=$requestData[1];
        $user_id = Auth::user()->id;
        $select = FlowMaster::where('id',$flowid)->where('orgnization_id',$user_id)->first();
        $result = DB::select("SELECT a.id,g.flow_name,g.created_at,b.office_name,c.department_name,d.position_name,e.emp_type,CONCAT(f.name,' - ',f.total_leave) as name,a.created_at FROM `approval_flows` as a INNER JOIN office_masters as b on a.office_id=b.id INNER JOIN department_masters as c on a.department_id=c.id INNER JOIN position_masters as d on a.position_id=d.id INNER JOIN leave_types as f on a.leave_type=f.id INNER JOIN emp_types as e on f.emp_type=e.id INNER JOIN flow_masters as g on a.flow_id=g.id WHERE a.orgnization_id=$user_id AND a.id=$ids AND a.flow_id=$flowid ORDER BY a.id DESC");


        $datas = DB::select("SELECT a.id,b.flow_name,c.office_name,d.department_name,e.position_name,f.name,a.created_at FROM `leave_authorities` as a INNER JOIN flow_masters as b on a.flow_id=b.id INNER JOIN office_masters as c on a.office_id=c.id INNER JOIN department_masters as d on a.department_id=d.id INNER JOIN position_masters as e on a.position_id=e.id INNER JOIN users as f ON a.user_id=f.id WHERE a.id=$ids AND a.flow_id=$flowid AND a.orgnization_id=$user_id");
    
        return response()->json(['status'=>200,'msg'=>'Succefully fetach','flow'=>$select,'datas'=>$result,'authorities'=>$datas]);
    }

/*-----------END GET AUTHORITY APPROVAL FLOW DATA-----------------*/

    public function SendPushNotification($data,$users,$user_type=0){
        $url = "https://fcm.googleapis.com/fcm/send";
        $subscription_key  = "key=AAAAG7wrjHo:APA91bH4jiRhFeKIJH162DXswTxQj5lqfl3Pv98UcEzE6k4AkjAn-u6P-mEyoWEqiEV6epMeNCiWieIFiO3Fc4fNQLkt7vH_CX8Ki59Gr-uKzCixQdxoKA8vhOvwqTHo-oGTG-Pdf8TL";
        $request_headers = array(
            "Authorization:" . $subscription_key,
            "Content-Type: application/json"
        );
        $postRequest = [
            "notification"=>[
                "title"     =>$data['title'],
                "body"      =>$data['body'],
                "icon"      =>"https://lnxx-hrms.sspl20.com/organization/logo/lnxxx.png",
                "click_action"=>"https://lnxx-hrms.sspl20.com/"
            ],
            "to"=>$users->fcm_id
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postRequest));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
        $season_data = curl_exec($ch);
        if (curl_errno($ch)) {
            print "Error: " . curl_error($ch);
            exit();
        }
        curl_close($ch);
        $datas['user_id']       =$users->id;
        $datas['title']         =$data['title'];
        $datas['description']   =$data['body'];
        $datas['msg_status']    =$season_data;
        $datas['created_at']    =date('Y-m-d H:i:s');
        $datas['user_type']     =$user_type;
        DB::table('notifications_history')->insert($datas);
    }
    public function StoreToken(Request $request){
        $user_id = Auth::user()->id;
        $user = User::where('id',$user_id)->first();
        $user->fcm_id = $request->token;
        $user->save();
        return response()->json(['status'=>200,'msg'=>'Succefully fetach','data'=>$user]);
    }
    public function OTPSend(){
        return view('otp');
    }
    public function HiringProcessStatus($id){
        $status = DB::select("SELECT a.id,b.name,a.user_id,a.status_for,a.status,a.status_remark,a.created_at FROM `all_status` as a INNER JOIN users as b on a.orgnization_id=b.id WHERE a.user_id=$id AND status_for='hiring_process' ORDER BY a.id DESC");
        return response()->json(['status'=>200,'msg'=>'Succefully fetach','data'=>$status]);
    }
    public function GetMeetingLinkdata(Request $request){
        $history = InterviewHistory::where('id',$request->id)->first();
        return response()->json(['status'=>200,'msg'=>'Succefully fetach','data'=>$history]);
    }
    public function UploadStatusDocument(Request $request){
        $user_id = Auth::user()->id;
        $input=$request->all();
        $images=array();
        if($files=$request->file('upload_document')){
            $sr=0;
            foreach($files as $file){
                $name=$file->getClientOriginalName();
                $file->move('public/uploads/status_document',$name);
                $status = new InterviewDocument();
                $status->orgnization_id = $user_id;
                $status->candidate_id = $request->candidate_id;
                $status->document_id = $request->document_id;
                $status->documnet_title = $request->filename[$sr++];
                $status->documnet_file = $name;
                $status->save();
                $status->createdat = date_format(date_create($status->created_at),"d-M-Y H:i");
                
            }
        }
        $hiring_approval = HiringApproval::select('employee_id')->where('organisation_id',$user_id)->where('status_id',$request->document_id)->first();
        if(!empty($hiring_approval)){
            $empt = EmpDetail::select('salutation','first_name','middle_name','last_name')->where('id',$request->candidate_id)->first();
            $hiring_sta = InterviewHiringStatu::select('id','status_name')->where('orgnization_id',$user_id)->where('id',$request->document_id)->first();
            $users_mail = User::select('id','name','email','fcm_id')->whereIn('id',explode(",",$hiring_approval->employee_id))->get();
            foreach($users_mail as $row){
                $body = 'Dear '.$row->name.' '.$hiring_sta->status_name.' varification for '.$empt->salutation.' '.$empt->first_name.' '.$empt->middle_name.' '.$empt->last_name.' please check';
                if(!empty($row->fcm_id)){
                    $not['title']='Approval '.$hiring_sta->status_name;
                    $not['body']= $body;
                    $this->SendPushNotification($not,$row,2);
                }
                $this->SendStatusApprovalMail($row,$empt,$body,$hiring_sta->status_name);
            }
        }
        return response()->json(['status'=>200,'msg'=>'Succefully uploaded','data'=>$status,'document_id'=>$request->document_id]);
    }
    public function SendStatusApprovalMail($data,$empt,$body,$hiring_sta){
        $email = array($data->email, 'naavjot@shailersolutions.com');
        try {
            $template = [
                'emp_name'=> $empt->salutation.' '.$empt->first_name.' '.$empt->middle_name.' '.$empt->last_name,
                'approver_name'=> $data->name,
                'subject'=>$body,
                'status_name'=>$hiring_sta,
                'user_name'=> Auth::user()->name
            ];
            Mail::send(['html'=>'email.status_approval'], $template,
                function ($message) use ($email,$template) {
                    $message->to($email)->from("vikaspyadava@gmail.com")->subject($template['subject']);
            });
            return true;
        } catch (Exception $ex) {
            return false;
        }  
    }
    public function GetUploadedDocumentStatus(Request $request){
        $user_id = Auth::user()->id;
        $statux = InterviewDocument::select('id','documnet_title','documnet_file')->where('orgnization_id',$user_id)->where('document_id',$request->document_id)->where('candidate_id',$request->candidate_id)->get();
        return response()->json(['status'=>200,'msg'=>'Data Fetch Succefully','data'=>$statux,'document_id'=>$request->document_id]);
    }
    public function RemoveDocumet(Request $request){
        $user_id = Auth::user()->id;
        InterviewDocument::where('id',$request->id)->delete();
        $count = InterviewDocument::select('id','documnet_title','documnet_file')->where('orgnization_id',$user_id)->where('document_id',$request->document_id)->where('candidate_id',$request->candidate_id)->count();
        return response()->json(['status'=>200,'msg'=>'Succefully Removed','count'=>$count]);
    }
    public function EmployeeAgainstUser(Request $request){
        $office = implode(',',$request->office_id);
        $user_id = Auth::user();
        $users = DB::select("SELECT b.id,a.employee_code,b.name,b.email FROM `employee_infos` as a INNER JOIN users as b on a.user_id=b.id WHERE employee_code is NOT null AND a.organisation_id=$user_id->id AND office_id in ($office)");
        return response()->json(['status'=>200,'msg'=>'Succefully Fetch Data','users'=>$users]);
    }
    public function UpdateUsersStatus(Request $request){
        $data = User::select('id','status')->where('id',$request->id)->first();
        $data->status = $request->status;
        $data->save();
        if(!empty($data)){
            return response()->json(['status'=>200,'data'=>$data]);
        }else{
            return response()->json(['status'=>400,'data'=>'']);
        }
    }
    public function UpdateOrganizationStatus(Request $request){
        $data = Organisation::where('id', $request->id)->select('id', 'status')->first();
       // dd($data);
        $data->status = $request->status;
        $data->save();
        if(!empty($data)){
            return response()->json(['status'=>200,'data'=>$data]);
        }else{
            return response()->json(['status'=>400,'data'=>'']);
        }
    }
    public function GetOrgnisationCategory(Request $request){
        $user_id = Auth::user()->id;
        $data = FormEngineCategory::select('id','name')->where('orgnization_id',$user_id)->get();
        if(!empty($data)){
            return response()->json(['status'=>200,'data'=>$data]);
        }else{
            return response()->json(['status'=>400,'data'=>'']);
        }
    }

    /*------------START GET EMP OFFICE BASE DEPARTMENT-------------*/


public function GetEmpOffice(Request $request){ 

        $office_id=$request->id; 
        
        $user_id = Auth::user()->id;
        $select = DepartmentMaster::where('orgnization_id',$user_id)->where('office_id',$office_id)->where('parent_id',0)->where('type_of_department','>',0)->count();
        if($select!=0){
            $data = DepartmentMaster::select('id','department_name')->where('orgnization_id',$user_id)->where('office_id',$office_id)->where('type_of_department',0)->get();
        }else{
            $data = DepartmentMaster::select('id','department_name')->where('orgnization_id',$user_id)->where('office_id',$office_id)->get();
            
        }

        //echo "<pre>"; print_r($data); echo "</pre>"; die;
        if(!empty($data)){
            return response()->json(['status'=>200,'data' => $data]);
        }else{
            return response()->json(['status'=>400,'data' => []]);
        }
    }

/*------------END GET EMP OFFICE BASE DEPARTMENT-------------*/

/*------------START GET EMP DEPARTMENT BASE DATA-------------*/


public function GetEmpDepartment(Request $request){
        $user_id = Auth::user()->id;
        $department_id=$request->department_id; 
        $dept_data = DepartmentMaster::select('id','department_name')->where('orgnization_id',$user_id)->where('id',$department_id)->first(); 
       $dept_name=$dept_data->department_name; 

       $select = DepartmentMaster::where('orgnization_id',$user_id)->where('id',$department_id)->where('parent_id',0)->where('type_of_department','>',0)->count();


       $position = DB::select("SELECT a.id,a.position_name,a.status,a.created_at,a.updated_at,b.office_name,c.department_name,a.type_of_position,a.sub_position FROM `position_masters` AS a INNER JOIN office_masters AS b ON b.id=a.office_id INNER JOIN department_masters AS c ON c.id=a.department_id WHERE a.orgnization_id=$user_id and a.id=$department_id ORDER BY a.id DESC");

       // echo "<pre>"; print_r($position); echo "</pre>"; die;



        if($select!=0){
            $data = DepartmentMaster::select('id','department_name')->where('orgnization_id',$user_id)->where('id',$department_id)->where('type_of_department',0)->get();
        }else{
            $data = DepartmentMaster::select('id','department_name')->where('orgnization_id',$user_id)->where('id',$department_id)->get();
            
        }


        if(!empty($data)){
            return response()->json(['status'=>200,'data' => $data]);
        }else{
            return response()->json(['status'=>400,'data' => []]);
        }
    }

/*------------END GET EMP DEPARTMENT BASE DATA-------------*/

/*=============START GET DAILY ATTENDENCE ===================*/

public function GetDailyAttendence(){
        $orgnaization = Auth::user()->id;
        $emp_detail = EmpDetail::select('id')->where('created_by',$orgnaization)->get();

        if(!empty($emp_detail)){
            foreach($emp_detail as $row){
                $data[]=$row->id;
            }
            $id = implode(',',$data);
 

            $data = EmpAttendance::select('id','user_id','in_time','total_time','out_time','in_image','out_image','created_at')->whereIn('id', $data)->whereIn('id', $data)->orderBy('id', 'DESC')->get();
            //echo "<pre>"; print_r($data); echo "</pre>"; die;

         }else{
            $data=[];
        }
        
        if(!empty($data)){
            return response()->json(['status'=>200,'data' => $data]);
        }else{
            return response()->json(['status'=>400,'data' => []]);
        }
        
    }

/*=============END GET DAILY ATTENDENCE  ====================*/






    public function generate(){
        return Hash::make('1066@12345');
    }
	
	public function assignYearlyLeavesToEmployee(){
		
		$get_users = DB::select("SELECT em_i.id as emp_id,em_i.employee_code, em_i.Organisation_id, em_i.office_id, em_i.user_id, em_i.department_id, b.name, b.email, et.id as emp_type, et.emp_type as employee_type_name 
							FROM `employee_infos` as em_i INNER JOIN users as b on em_i.user_id=b.id INNER JOIN emp_types as et ON et.orgnization_id=em_i.organisation_id AND et.office_id=em_i.office_id 
							WHERE em_i.employee_code is NOT null");
		
		if(count($get_users) > 0){
			//echo "<pre>";print_r($get_users);echo "</pre>";
			foreach($get_users as $user_details){
				 
				$user_id = $user_details->user_id;
				$employee_id = $user_details->emp_id;
				$Organisation_id = $user_details->Organisation_id;
				$office_id = $user_details->office_id;
				$department_id = $user_details->department_id;
				//$get_leaves = DB::select("SELECT a.id as leave_type_id, a.name, a.total_leave, a.emp_type, b.id as remaining_leave_id, b.total_leaves as remaining_leave FROM leave_types as a LEFT OUTER JOIN remaining_leaves as b ON a.orgnization_id=b.organization_id AND a.office_id=b.office_id AND a.emp_type= b.employee_type AND a.id= b.leave_type_id where a.`orgnization_id`=$user_details->Organisation_id AND a.`office_id`=$user_details->office_id AND a.`emp_type`=$user_details->emp_type");
				//echo "SELECT a.id as leave_type_id, a.name, a.total_leave, a.emp_type FROM leave_types as a where a.`orgnization_id`=$Organisation_id AND a.`office_id`=$office_id AND a.`emp_type`=$user_details->emp_type";
				$get_leaves = DB::select("SELECT a.id as leave_type_id, a.name, a.total_leave, a.emp_type FROM leave_types as a where a.`orgnization_id`=$Organisation_id AND a.`office_id`=$office_id AND a.`emp_type`=$user_details->emp_type");
				//echo "<pre>";print_r($user_details);echo "</pre>";
				
				if(count($get_leaves) > 0){
					//echo "<pre>".'get_leaves--';print_r($get_leaves);echo "</pre>";
					foreach($get_leaves as $leave_desc){
						
						$check_leave_record = DB::select("SELECT id, total_leaves as remaining_leave FROM remaining_leaves where`user_id`=$user_id AND `organization_id`=$Organisation_id AND `office_id`=$user_details->office_id AND `leave_type_id`=$leave_desc->leave_type_id AND `employee_type`=$user_details->emp_type");
						///////////////////////// Begin transacations /////////////////////
						DB::beginTransaction();
						try{
							//echo "<pre>".'check_leave_record--';print_r($check_leave_record);echo "</pre>";
							if(count($check_leave_record)){
								$rem_leave = $check_leave_record[0]->remaining_leave;
								$record_id = $check_leave_record[0]->id;
								
								////////////////// If leave is Medical or Earned leave then carry forward other leave in new year is collapse //////////////////////////////
								
								//if($leave_desc->name=='Casual Leave'|| $leave_desc->name=='Special Casual leave'){
							
								if($leave_desc->name=='Medical Leave'|| $leave_desc->name=='Earned leave'){									
									$total_leave = $rem_leave + $leave_desc->total_leave;
								}else {
									$total_leave = $leave_desc->total_leave;
								}
								
								DB::update("UPDATE `remaining_leaves` SET `total_leaves`=$total_leave where id=$record_id");							
							}else{
								$total_leave = $leave_desc->total_leave;
								DB::insert("INSERT into `remaining_leaves` (`user_id`, `employee_id`, `organization_id`, `office_id`, `leave_type_id`, `department_id`, `employee_type`, `total_leaves`) VALUES ($user_id, $employee_id, $Organisation_id, $office_id, $leave_desc->leave_type_id, $department_id, $leave_desc->emp_type, $total_leave)");
							}
							DB::commit();
							
						}catch(Exception $e){
							$error = $e;
							DB::rollback();
							return response()->json(['status'=>400,'message' => 'Error updaing in table. please contact to admin. '.  $e->getMessage()]);
						} catch (\Throwable $e) {
							DB::rollback();
							throw $e;
							return response()->json(['status'=>400,'message' => 'Error updaing in table. please contact to admin. '.  $e->getMessage()]);
						}
						$total_leave = ''; 
					}				
				}
			}
		}
		
		return response()->json(['status'=>200,'message' => 'Leaves assigned to all user Successfully']);
    }
}