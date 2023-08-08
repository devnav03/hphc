<?php

namespace App\Http\Controllers\user\leave;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use App\Models\EmpDetail;
use Illuminate\Support\Facades\Hash;
use App\Models\Organisation;
use App\Models\LeaveAuthority;
use App\Models\ApprovalFlow;
use App\Models\EmployeeInfo;
use App\Models\Leave;
use App\Models\LeaveType;
use App\Models\OfficeMaster;
use DB;
use Illuminate\Support\Facades\Mail;
class LeaveController extends Controller
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
    public function GetOrganisation($user_id){
        $empdetail = EmpDetail::select('created_by')->where(['user_id'=>$user_id])->first();
        return Organisation::where(['user_id'=>$empdetail->created_by])->first();
    }
    public function SendApproversMail($user_id,$takeleave){
        $users = User::where(['id'=>$user_id])->first();
        if(!empty($users)){
            $applied = User::select('name')->where(['id'=>$takeleave->user_id])->first();
            if(!empty($applied)){
                $leave_tyle = LeaveType::select('name')->where(['id'=>$takeleave->leave_type])->first();
                $template = [
                    'applied_name'  =>$applied->name,
                    'name'          =>$users->name,
                    'email'         =>$users->email,
                    'leave_type'    =>$leave_tyle->name,
                    'from'          =>$takeleave->start_date,
                    'to'            =>$takeleave->end_date,
                    'reason_for'    =>$takeleave->reason_for_leav_comp,
                ];
                try {
                    Mail::send(['html'=>'email.leave_approvers'], $template,
                        function ($message) use ($template) {
                            $message->to($template['email'])->from('vikas@shailersolutions.com')->subject($template['applied_name'].' '.$template['leave_type'].' Approval From '.$template['from'].' To '.$template['to'].'');
                    });
                    return true;
                } catch (Exception $ex) {
                    return false;
                }
            }
        }
    }

    public function TakeLeave(Request $request){
        $user = Auth::user();
        $organisation = Organisation::where(['user_id'=>$user->organisation_id])->first();
        $emp = EmployeeInfo::select('office_id','department_id')->where('organisation_id',$organisation->user_id)->where('employee_code','!=','')->where('user_id',$user->id)->first();
        $date=date('Y-m-d');
        $data = LeaveType::select('id','name','total_leave')->where('orgnization_id',$organisation->user_id)->where('department_id',$emp->department_id)->where('office_id',$emp->office_id)->get();
        $leave_type=array();
        foreach($data as $rows){
            $select = DB::select("SELECT SUM(duration) as leave_type FROM `leaves` WHERE leave_type=$rows->id AND status='Approved' AND user_id=$user->id AND YEAR(created_at)='$date' LIMIT 1");
            if(!empty($select[0]->leave_type)){
                $rows->totalleave = $rows->total_leave - $select[0]->leave_type;
            }else{
                $rows->totalleave = $rows->total_leave;
            }
            $leave_type[] = $rows;
        }
        if(!empty($_POST)){
            $takeLeave = new Leave();
            $takeLeave->user_id = $user->id;
            $takeLeave->office_id = $emp->office_id;
            $takeLeave->department_id = $emp->department_id;
            $takeLeave->start_date = $request->start_date;
            $takeLeave->end_date = $request->end_date;
            $takeLeave->duration = $request->duration;
            $takeLeave->leave_type = $request->leave_type;
            $takeLeave->reason_for_leav_comp = $request->reason_for_leav_comp;
            $takeLeave->save();
            $approvers = $this->Approvers($user,$takeLeave);
            if(!empty($approvers)){
                foreach($approvers as $rows){
                    $this->SendApproversMail($rows->user_id,$takeLeave);
                }
            }
            return redirect('leave-history')->with('success','Saved successfuly');
        }
        return view('user.leave.take_leave',compact('organisation','leave_type'));
    }

    public function Approvers($user,$leaves){
        $emp = EmployeeInfo::select('position_id')->where('organisation_id',$user->organisation_id)->whereNotNull('employee_code')->where('user_id',$user->id)->first();
        $approval_flow = ApprovalFlow::select('flow_id')->where('orgnization_id',$user->organisation_id)->where('office_id',$leaves->office_id)->where('department_id',$leaves->department_id)->where('leave_type',$leaves->leave_type)->where('position_id',$emp->position_id)->first();
        if(!empty($approval_flow)){
            $leave_uthority =  LeaveAuthority::select('user_id')->where('flow_id',$approval_flow->flow_id)->where('orgnization_id',$user->organisation_id)->where('office_id',$leaves->office_id)->where('department_id',$leaves->department_id)->where('position_id',$emp->position_id)->get();
            if(!empty($leave_uthority)){
                return $leave_uthority;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
    public function LeaveHistory(Request $request){
        $user = Auth::user();
        $organisation = Organisation::where(['user_id'=>$user->organisation_id])->first();
        return view('user.leave.leave_history',compact('organisation'));
    }
	
	public function listLeaveforApproval(){
		
		$user = Auth::user();
		$user_id = Auth::user()->id;
		$organisation_id = Auth::user()->organisation_id;
		
		$check_role = DB::table('role_user')
		->join('roles', 'role_user.role_id', '=', 'roles.id')
		->select('roles.slug') // You can select specific columns from both tables
		->where('role_user.user_id',$user_id)
		->first();
		
		if(!empty($check_role->slug)){
			$slug = $check_role->slug;
		}else{
			$slug = "";
		}
		
		
	
		$organisation = Organisation::where(['user_id'=>$organisation_id])->first();
		$office = OfficeMaster::select('id','office_name')->where('orgnization_id',$organisation_id)->get();	
		
		if($slug=="leave-authority"){
			
			$data = DB::select("SELECT a.name as leave_type_name, b.id, b.user_id, 
			b.start_date,b.end_date,b.duration,b.time_duration,b.reason_for_leav_comp,
			b.created_at,b.status,c.name,c.mobile 
			FROM `leave_types` as a 
			INNER JOIN leaves as b on a.id=b.leave_type 
			INNER JOIN users as c on c.id=b.user_id  WHERE a.orgnization_id=$organisation_id order by b.id DESC" );
		}else{			
		
			////////////////// Get Flow id array in which user is leave approver //////////////////////
			
			$all_flow_of_user_as_approver = LeaveAuthority::select('flow_id')
											->where('orgnization_id',$organisation_id)
											->where('user_id',$user_id)
											->get();
			
			if(count($all_flow_of_user_as_approver)){
			
				$flow_id_of_user_as_approver_string = $all_flow_of_user_as_approver->implode('flow_id', ', ');		
			
				$data = DB::select("SELECT DISTINCT b.id, b.user_id, b.leave_type, b.office_id, b.department_id, b.start_date, b.end_date, 
				b.duration, b.time_duration, b.reason_for_leav_comp,
				b.created_at, b.status, c.name, c.mobile, d.name as leave_type_name 
				FROM `approval_flows` as a 
				INNER JOIN leaves as b on a.`leave_type`=b.leave_type 
				INNER JOIN users as c on c.id=b.user_id
				INNER JOIN `leave_types` as d on d.id=b.leave_type 
				where a.flow_id IN ($flow_id_of_user_as_approver_string) ORDER BY `b`.`start_date` DESC");
			
			}else{
				$data = [];
			}
		}
		return view('user.leave.leave_for_approval_details',compact('organisation','office','data'));
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
}