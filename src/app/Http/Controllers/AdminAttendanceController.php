<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Worktime;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use App\Models\Breaktime;
use App\Models\TempWorktime;
use App\Models\TempBreaktime;
use App\Models\Application;
use App\Http\Requests\AttendanceRequest;
use App\Models\User;
use App\Http\Requests\WorkRequest;
use App\Http\Requests\AttendRequest;
use Illuminate\Support\Facades\DB;


class AdminAttendanceController extends Controller
{
    //
    public function getList(){
        $particularDate = Carbon::now();

        $worktimes = Worktime::where('date', $particularDate->format('Y-m-d'))->get();

        $users = User::all();
                
        return view('admin.admin-attendance-list', compact('worktimes', 'particularDate', 'users'));
    }

    public function getPreviousDate(Request $request){
        $particularDate = Carbon::parse($request->previousParticularDate);
        $worktimes = Worktime::where('date', $particularDate->format('Y-m-d'))->get();
        $users = User::all();

        return view('admin.admin-attendance-list', compact('worktimes','particularDate','users'));
    }

    public function getLaterDate(Request $request){
        $particularDate = Carbon::parse($request->laterParticularDate);
        $worktimes = Worktime::where('date', $particularDate->format('Y-m-d'))->get();
        $users = User::all();

        return view('admin.admin-attendance-list', compact('worktimes','particularDate', 'users'));
    }

    public function getDetail($id){
        $current_time = Carbon::now()->format('H:i');
        $current_date = Carbon::now()->format('Y-m-d');
        $worktime = Worktime::find($id);
        return view('admin.admin-break-multi-detail', compact('worktime'));
    }

    public function update(AttendanceRequest $request, $id){
        
        try{
            DB::beginTransaction();

            $worktime = Worktime::find($id);
            $worktime->update([
                'start_time' => $request->workStartTime,
                'end_time' => $request->workEndTime,
            ]);

            if(count($worktime->breaktimes) > 0){
                for($i=0; $i<count($worktime->breaktimes); $i++){
                    $breaktime = $worktime->breaktimes[$i];
                    $breaktime->update([
                        'start_time' => $request->breakStartTime[$i],
                        'end_time' => $request->breakEndTime[$i],
                    ]);
                }
            }else{
            }

            DB::commit();
            return redirect('/admin/attendance/list');
        }catch(Exception $e){
            DB::rollback();
            return redirect()->back()->with('error', "エラーが発生しました");
        }
    }

    public function getApprovalPage($attendance_correct_request){
        $application = Application::where('worktime_id', $attendance_correct_request)->first();
        $tempWorktime = TempWorktime::whereHas('application', function($query) use ($attendance_correct_request){
            $query->where('worktime_id', $attendance_correct_request);
        })->first();

        $worktime = Worktime::find($attendance_correct_request);
        
        return view('admin.admin-approval',compact('worktime', 'tempWorktime'));
    }

    public function approve(Request $request){
        $worktime = Worktime::find($request->worktimeId);
        $approval = $worktime->application->approval();
        $approval->update([
            'is_approved' => true,
        ]);
        $tempWorktime = TempWorktime::find($request->tempWorktimeId);

        $worktime->update([
            'date' => $tempWorktime->date,
            'user_id' => $tempWorktime->user_id,
            'start_time' => $tempWorktime->start_time,
            'end_time' => $tempWorktime->end_time,
        ]);


        return redirect('/admin/attendance/list');
    }

    public function getStaffList(){
        $users = User::all();
        return view('admin.staff-list', compact('users'));
    }

    public function getStaffAttendanceList($id){
        $user = User::find($id);
        return view('admin.staff-attendance-list');
    }
}