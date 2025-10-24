<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Worktime;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;


class AdminAttendanceController extends Controller
{
    //
    public function getList(){
        $particularDate = Carbon::now();

        $worktimes = Worktime::where('date', $particularDate->format('Y-m-d'))
                ->orderBy('date', 'asc')
                ->whereNotNull('end_time')
                ->get();
                
        return view('admin.admin-attendance-list', compact('worktimes', 'particularDate'));
    }

    public function getPreviousDate(Request $request){
        $particularDate = Carbon::parse($request->previousParticularDate);
        $worktimes = Worktime::where('date', $particularDate->format('Y-m-d'))
                ->orderBy('date', 'asc')
                ->whereNotNull('end_time')
                ->get();
        return view('admin.admin-attendance-list', compact('worktimes','particularDate'));
    }

    public function getLaterDate(Request $request){
        $particularDate = Carbon::parse($request->laterParticularDate);
        $worktimes = Worktime::where('date', $particularDate->format('Y-m-d'))
                ->orderBy('date', 'asc')
                ->whereNotNull('end_time')
                ->get();
        return view('admin.admin-attendance-list', compact('worktimes','particularDate'));
    }

    public function getDetail($id){
        $current_time = Carbon::now()->format('H:i');
        $current_date = Carbon::now()->format('Y-m-d');
        $worktime = Worktime::find($id);
        return view('admin.admin-attendance-detail', compact('worktime'));
    }

    public function update(Request $request, $id){
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
        
        return redirect('/admin/attendance/list');
    }

    public function getApplicationList(){
        $worktimes = Worktime::all();
        $appliedWorktimes = new Collection();

        foreach($worktimes as $worktime){
            if($worktime->application !== null){
                $appliedWorktimes->push($worktime);
            }
        }
        
        return view('admin.admin-application-list', compact('appliedWorktimes'));
    }

    public function getApproval($attendance_correct_request){
        $worktime = Worktime::find($attendance_correct_request);
        return view('admin.admin-approval',compact('worktime'));
    }

    public function getStaffList(){
        return view('admin.staff-list');
    }
}