<?php

namespace App\Http\Controllers\organization\hiring_process;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use App\Models\AllStatu;
use App\Models\Organisation;
use App\Models\ResourceRequirement;
use App\Models\EmpDetail;
use App\Models\DepartmentMaster;
use App\Models\PositionMaster;
use App\Models\OfficeMaster;
use App\Models\InterviewHistory;
use App\Models\InterviewHiringStatu;
use App\Models\HiringApproval;
use App\Models\InterviewDocument;
use DB;
class HiringProcessController extends Controller
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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    
    
    public function InterviewHiringStatus(Request $request){
        $user_id = Auth::user()->id;
        $organisation = Organisation::where(['user_id'=>$user_id])->first();
        $result = InterviewHiringStatu::select('id','status_name','created_at')->where('orgnization_id',$user_id)->orderBy('id', 'DESC')->get();
        $update=[];
        if(!empty($request->segment(2))){
            $update = InterviewHiringStatu::where('id',$request->segment(2))->first();
        }
        if(!empty($request->status_name)){
            if($request->id){
                $interview_status = InterviewHiringStatu::where('id',$request->id)->first();
                $interview_status->status_name = $request->status_name;
                $interview_status->save();
                return redirect('interview-hiring-status')->with('success','Saved successfuly');
            }else{
                $interview_status = new InterviewHiringStatu();
                $interview_status->orgnization_id = Auth::user()->id;
                $interview_status->status_name = $request->status_name;
                $interview_status->save();
                return redirect('interview-hiring-status')->with('success','Saved successfuly');
            }
        }
        return view('organization.hiring_process.interview_hiring_status',compact('organisation','result','update'));
    }
    public function InterviewHiringStatusApproval(Request $request){
        $user_id = Auth::user()->id;
        $organisation = Organisation::where(['user_id'=>$user_id])->first();
        $result = InterviewHiringStatu::select('id','status_name','created_at')->where('orgnization_id',$user_id)->orderBy('id', 'DESC')->get();
        $office = OfficeMaster::select('id','office_name')->where('orgnization_id',$user_id)->where('status','Active')->get();
        $update=[];
        if(!empty($request->segment(2))){
            $update = HiringApproval::where('id',$request->segment(2))->first();
        }
        if(!empty($request->status_id)){
            if($request->id){
                $interview_status = HiringApproval::where('id',$request->id)->first();
                $interview_status->status_id = $request->status_id;
                $interview_status->organisation_id = $user_id;
                $interview_status->office_id = implode(',',$request->office_id);
                $interview_status->employee_id = implode(',',$request->employee_id);
                $interview_status->save();
                return redirect('interview-hiring-status')->with('success','Saved successfuly');
            }else{
                $interview_status = new HiringApproval();
                $interview_status->status_id = $request->status_id;
                $interview_status->organisation_id = $user_id;
                $interview_status->office_id = implode(',',$request->office_id);
                $interview_status->employee_id = implode(',',$request->employee_id);
                $interview_status->save();
                return redirect('interview-hiring-status')->with('success','Saved successfuly');
            }
        }
        return view('organization.hiring_process.interview_hiring_status_approval',compact('organisation','result','update','office'));
    }
    public function HiringStatusApproval(Request $request){
        $user_id = Auth::user()->id;
        if(!empty($request->status_id)){
            $interview_status = new HiringApproval();
            $interview_status->status_id = $request->status_id;
            $interview_status->organisation_id = $user_id;
            $interview_status->candidate_id = $request->candidate_id;
            if($request->approval==3){
                $interview_status->office_id = implode(',',$request->office_id);
                $interview_status->employee_id = implode(',',$request->employee_id);
            }
            $interview_status->status = $request->approval;
            $interview_status->save();
            if($files=$request->file('upload_document')){
            $sr=0;foreach($files as $file){
                    $name=$file->getClientOriginalName();
                    $file->move('public/uploads/status_document',$name);
                    $status = new InterviewDocument();
                    $status->hiring_approvals_id = $interview_status->id;
                    $status->documnet_title = $request->filename[$sr++];
                    $status->documnet_file = $name;
                    $status->save();
                    $status->createdat = date_format(date_create($status->created_at),"d-M-Y H:i");
                }
            }
            if(!empty($interview_status->employee_id)){
                $empt = EmpDetail::select('salutation','first_name','middle_name','last_name')->where('id',$request->candidate_id)->first();
                $hiring_sta = InterviewHiringStatu::select('id','status_name')->where('orgnization_id',$user_id)->where('id',$request->status_id)->first();
                $users_mail = User::select('id','name','email','fcm_id')->whereIn('id',explode(",",$interview_status->employee_id))->get();
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
            return redirect('onboard-candidate-documents/'.$request->candidate_id.'')->with('success','Saved successfuly');
        }else{
            return redirect('onboard-candidate-documents/'.$request->candidate_id.'')->with('error','Data not saved');
        }
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
                    $message->to($email)->from("dipanshu.roy68@gmail.com")->subject($template['subject']);
            });
            return true;
        } catch (Exception $ex) {
            return false;
        }  
    }
    public function SendPushNotification($data,$users,$user_type=0){
        if(!empty($users->fcm_id)){
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
        }
        $datas['user_id']       =$users->id;
        $datas['title']         =$data['title'];
        $datas['description']   =$data['body'];
        $datas['msg_status']    =$season_data;
        $datas['created_at']    =date('Y-m-d H:i:s');
        $datas['user_type']     =$user_type;
        DB::table('notifications_history')->insert($datas);
    }
    public function DeleteHiringStatus(Request $request){
        InterviewHiringStatu::where('id',$request->segment(2))->delete();
        return redirect('interview-hiring-status')->with('success', 'Deleted successfully');  
    }
    public function createResourceReq(Request $request){
        $user_id = Auth::user()->id;
        $organisation = Organisation::where(['user_id'=>$user_id])->first();
        $office = OfficeMaster::select('id','office_name')->where('orgnization_id',$user_id)->where('status','Active')->orderBy('office_name', 'ASC')->get();
        $update=[];
        $department=[];
        $position=[];
        if(!empty($request->segment(2))){
            $update = ResourceRequirement::where('id',$request->segment(2))->first();
            $department = DepartmentMaster::where('office_id',$update->office_id)->get();
            $position = PositionMaster::where('department_id',$update->department_id)->get();
        }
        if(!empty($request->job_title)){
            $resourceRequirement = new ResourceRequirement();
            $resourceRequirement->orgnization_id = Auth::user()->id;
            $resourceRequirement->office_id = $request->office_id;
            $resourceRequirement->department_id = $request->department_id;
            $resourceRequirement->position_id = $request->position_id;
            $resourceRequirement->job_title = $request->job_title;
            $resourceRequirement->no_of_vacancy = $request->no_of_vacancy;
            $resourceRequirement->minimum_salary = $request->minimum_salary;
            $resourceRequirement->maximum_salary = $request->maximum_salary;
            $resourceRequirement->job_type = $request->job_type;
            $resourceRequirement->description = $request->description;
            $resourceRequirement->save();
            return redirect('create-resource-requirement')->with('success','Saved successfuly');
        }
        return view('organization.hiring_process.resource_requirement',compact('organisation','office','department','position'));
    }
    public function RequirementDetails(){
        $user_id = Auth::user()->id;
        $organisation = Organisation::where(['user_id'=>$user_id])->first();
        $job_title = ResourceRequirement::where('orgnization_id',$user_id)->groupBy('job_title')->get();
        $minimum_salary = ResourceRequirement::where('orgnization_id',$user_id)->groupBy('minimum_salary')->get();
        $maximum_salary = ResourceRequirement::where('orgnization_id',$user_id)->groupBy('maximum_salary')->get();
        return view('organization.hiring_process.requirement_details',compact('organisation','job_title','minimum_salary','maximum_salary'));
    }
    public function CandidateList(Request $request){
        //dd($request->all());
        $user_id = Auth::user()->id;
        $organisation = Organisation::where(['user_id'=>$user_id])->first();
        if(!empty($request->status) && empty($request->to_date) && empty($request->from_date)){
            $result = EmpDetail::where('status',$request->status)->orderBy('id','DESC')->get();
        }elseif(!empty($request->from_date) && !empty($request->to_date) && !empty($request->status)){
            $result = EmpDetail::where('status',$request->status)->whereBetween('created_at',[$request->from_date,$request->to_date])->orderBy('id','DESC')->get();
        }elseif(!empty($request->from_date) && !empty($request->to_date) && empty($request->status)){
            $result = EmpDetail::whereBetween('created_at',[$request->from_date,$request->to_date])->orderBy('id','DESC')->get();
        }else{
            $result = EmpDetail::where('status','Pending')->orderBy('id','DESC')->get();
        }
        $status=(object)['Cancel','Pending','Rejected','Shortlist','Return'];
        return view('organization.hiring_process.candidate_list',compact('organisation','result','status'));
    }
    public function ShortlistedCandidateList(Request $request){
        $user_id = Auth::user()->id;
        $organisation = Organisation::where(['user_id'=>$user_id])->first();
        if(!empty($request->status) && empty($request->to_date) && empty($request->from_date)){
            $result = EmpDetail::where('status',$request->status)->orderBy('id','DESC')->get();
        }elseif(!empty($request->from_date) && !empty($request->to_date) && !empty($request->status)){
            $result = EmpDetail::where('status',$request->status)->whereBetween('created_at',[$request->from_date,$request->to_date])->orderBy('id','DESC')->get();
        }elseif(!empty($request->from_date) && !empty($request->to_date) && empty($request->status)){
            $result = EmpDetail::whereBetween('created_at',[$request->from_date,$request->to_date])->orderBy('id','DESC')->get();
        }else{
            $result = EmpDetail::where('status','Shortlist')->orderBy('id','DESC')->get();
        }
        $status=(object)['Cancel','Pending','Rejected','Shortlist','Return'];
        return view('organization.hiring_process.shortlisted_candidate_list',compact('organisation','result','status'));
    }
    public function CandidateChangeStatus(Request $request){
        $user_id = Auth::user()->id;
        $emp = EmpDetail::where('id',$request->candidate_id)->first();
        $emp->status=$request->status;
        $emp->status_remark=$request->status_remark;
        $emp->save();
        $allstatus = new AllStatu();
        $allstatus->user_id = $request->candidate_id;
        $allstatus->orgnization_id = $user_id;
        $allstatus->status_for = 'hiring_process';
        $allstatus->status = $request->status;
        $allstatus->status_remark = $request->status_remark;
        $allstatus->save();
        return redirect('candidate-list')->with('success','Saved successfuly');
    }
    public function ScheduleInterview(Request $request,$id){
        $user_id = Auth::user()->id;
        $organisation = Organisation::where(['user_id'=>$user_id])->first();
        $history = InterviewHistory::where('interview_id',$id)->orderBy('id','DESC')->get();
        $result = EmpDetail::where('id',$id)->first();
        return view('organization.hiring_process.schedule_interview',compact('organisation','result','history'));
    }
    public function OnboardCandidateDocuments(Request $request,$id){
        $user_id = Auth::user()->id;
        $organisation = Organisation::where(['user_id'=>$user_id])->first();
        $empdetails = EmpDetail::select('id','salutation','first_name','middle_name','last_name')->where('id',$id)->first();
        $result = InterviewHiringStatu::select('id','status_name','created_at')->where('orgnization_id',$user_id)->orderBy('id', 'DESC')->get();
        $office = OfficeMaster::select('id','office_name')->where('orgnization_id',$user_id)->where('status','Active')->get();
        $rowdata = DB::select("SELECT a.id,b.status_name,a.office_id,a.employee_id,a.status,a.status_id,a.approved_by,a.created_at,a.updated_at FROM `hiring_approvals` as a INNER JOIN interview_hiring_status as b on a.status_id=b.id INNER JOIN emp_details as c on a.candidate_id=c.id WHERE a.organisation_id=$user_id AND a.candidate_id=$id ORDER BY a.id ASC");
        return view('organization.hiring_process.hiring_status',compact('organisation','result','empdetails','result','office','rowdata'));
    }
    public function SaveInterview(Request $request){
        $user_id = Auth::user()->id;
        $result = EmpDetail::where('id',$request->candidate_id)->first();
        $this->SendInterviewMail($request,$result);
        $interview_history = new InterviewHistory();
        $interview_history->interview_id = $request->candidate_id;
        $interview_history->meeting_type = $request->meeting_type;
        $interview_history->meeting_date = $request->meeting_date;
        $interview_history->from_meeting = $request->meeting_from_duration;
        $interview_history->to_meeting = $request->meeting_to_duration;
        $interview_history->candidate_email = $request->candidate_email;
        $interview_history->meeting_link_description = $request->meeting_description;
        $interview_history->save();
        return redirect('shortlisted-candidate-list')->with('success','Saved successfuly');
    }
    public function SendInterviewMail($data,$result){
        $email = array($data->candidate_email);
        try {
            $template = [
                'name'=> $result->salutation.' '.$result->first_name.' '.$result->middle_name.' '.$result->last_name,
                'meeting_date'=>date_format(date_create($data->meeting_date),"d-M-Y"),
                'meeting_type'=>$data->meeting_type,
                'from'=> $data->meeting_from_duration,
                'to'=> $data->meeting_to_duration,
                'meeting_description'=> $data->meeting_description,
                'user_name'=> Auth::user()->name
            ];
            //dd($template);
            Mail::send(['html'=>'email.interview'], $template,
                function ($message) use ($email,$template) {
                    $message->to($email)->from("dipanshu.roy68@gmail.com")->subject($template['name'].' your 💻 interview schedule @ '.$template['meeting_date'].' '.$template['from'].' '.$template['to']);
            });
            return true;
        } catch (Exception $ex) {
            return false;
        }  
    }
}