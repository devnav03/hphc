<?php

namespace App\Http\Controllers\organization\timesheet;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use App\Models\EmpDetail;
use App\Models\Organisation;
use DB;
class TimesheetController extends Controller
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

    public function GetOrganisation($user_id){
        return Organisation::where(['user_id'=>$user_id])->first();
    }
    public function ViewEmployeeTimesheet(Request $request){
        $user_id = Auth::user()->id;
        $organisation = $this->GetOrganisation($user_id);
        $timesheet_details = DB::select("SELECT a.user_id as id,CONCAT(a.first_name,' ',a.last_name) as name FROM `emp_details` as a LEFT JOIN timeseets as b on a.user_id=b.user_id INNER JOIN users as c on c.id=a.user_id WHERE a.created_by=$user_id AND c.type=2 GROUP by a.id ORDER BY name ASC;");
        return view('organization.timesheet.view_employee_timesheetlist',compact('organisation','timesheet_details'));
    }
    
}
