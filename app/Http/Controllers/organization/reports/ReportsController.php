<?php
namespace App\Http\Controllers\organization\reports;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;

use App\Models\SourceMaster;
use App\Models\Organisation;
use App\Models\PositionMaster;
use App\Models\NoticeMaster;
use App\Models\EducationMaster;
use App\Models\ProjectMaster;
use App\Models\ProjectActivity;
use App\Models\User;
use App\Models\FormEngineCategory;
use App\Models\OfficeMaster;
use App\Models\DepartmentMaster;
use App\Models\HeaderFooterTemplateMaster;
use App\Models\ShiftMaster;
use App\Models\EmpAttendance;
use App\Models\EmpDetail;
use App\Models\LeaveType;
use App\Models\Leave;
use App\Models\BankMaster;
use App\Models\State;
use App\Models\City;
use App\Models\EmpType;
use App\Models\Country;
use App\Models\WeekDay;
use App\Models\AssignTask;
use App\Models\ShiftDuration;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\TemplateMaster;

use DB;
class ReportsController extends Controller
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

    public function reportMaster(Request $request){ 
        $user_id = Auth::user()->id;
         $date = date('Y-m-d');
         $time = date('H:i:s');

         $organisation = Organisation::where(['user_id'=>Auth::user()->id])->first();
         $users = User::where(['type'=>'1'])->get();

         $office = OfficeMaster::select('id','office_name','address','status')->where('status','Active')->where('orgnization_id',$user_id)->orderBy('office_name', 'ASC')->get();

         $overallreport= DB::select("SELECT a.id,CONCAT(b.name,' - ',g.employee_code) as name,a.user_id,a.in_time,a.total_time,a.out_time,a.in_image,a.out_image,a.created_at,d.office_name,e.department_name,f.shift_name from `emp_attendances` as a INNER JOIN `users` as b ON a.user_id=b.id INNER JOIN employee_infos as c on a.user_id=c.user_id  INNER JOIN `office_masters` as d ON c.office_id=d.id INNER JOIN `department_masters` as e ON c.office_id=e.office_id INNER JOIN `shift_masters` as f ON b.shift_id=f.id INNER JOIN `employee_infos` AS g ON a.user_id=g.user_id WHERE b.organisation_id = $user_id AND b.type='2' AND DATE(a.created_at) = '$date' GROUP BY d.office_name ORDER BY a.id DESC");
  
         $latecomersreport= DB::select("SELECT a.id,CONCAT(b.name,' - ',c.employee_code) as name,a.user_id,a.in_time,a.total_time,a.out_time,a.in_image,a.out_image,a.created_at,d.office_name,e.department_name,f.shift_name,g.shift_duration from `emp_attendances` as a INNER JOIN `users` as b ON a.user_id=b.id INNER JOIN employee_infos as c on a.user_id=c.user_id  INNER JOIN `office_masters` as d ON c.office_id=d.id INNER JOIN `department_masters` as e ON c.office_id=e.office_id INNER JOIN `shift_masters` as f ON b.shift_id=f.id INNER JOIN `shift_durations` as g ON b.shift_id=g.shift_id WHERE a.in_time > 
           g.in_time_relaxation AND  b.organisation_id = $user_id AND b.type='2' AND DATE(a.created_at) = '$date' GROUP BY e.department_name ORDER BY a.total_time DESC");
        
         $daily_attenence = DB::select("SELECT a.id,CONCAT(b.name,' - ',c.employee_code) as name,a.user_id,a.in_time,a.total_time,a.out_time,a.in_image,a.out_image,a.created_at,d.office_name,e.department_name,f.shift_name from `emp_attendances` as a INNER JOIN `users` as b ON a.user_id=b.id INNER JOIN employee_infos as c on a.user_id=c.user_id  INNER JOIN `office_masters` as d ON c.office_id=d.id INNER JOIN `department_masters` as e ON c.office_id=e.office_id INNER JOIN `shift_masters` as f ON b.shift_id=f.id INNER JOIN `employee_infos` AS g ON b.id=g.user_id WHERE b.organisation_id = $user_id AND b.type='2' AND DATE(a.created_at) = '$date' GROUP BY e.department_name ORDER BY a.total_time DESC");


         $absenteereport= DB::select("SELECT a.id,a.user_id,a.in_time,a.total_time,a.out_time,a.in_image,a.out_image,a.created_at,b.name,d.office_name,e.department_name,f.shift_name from `emp_attendances` as a INNER JOIN `users` as b ON a.user_id=b.id INNER JOIN employee_infos as c on a.user_id=c.user_id  INNER JOIN `office_masters` as d ON c.office_id=d.id INNER JOIN `department_masters` as e ON c.office_id=e.office_id INNER JOIN `shift_masters` as f ON b.shift_id=f.id WHERE b.organisation_id = $user_id AND a.`out_time` IS NULL ORDER BY a.id DESC");  

      
         return view('organization.reports.report_master',compact('organisation','overallreport','latecomersreport','daily_attenence'));
    }
}
