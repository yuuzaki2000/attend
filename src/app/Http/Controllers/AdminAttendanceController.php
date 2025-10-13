<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Worktime;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AdminAttendanceController extends Controller
{
    //
    public function getList(){
        /*$current_time = Carbon::now()->format('H:i');  */
        $current_date = Carbon::now()->format('Y-m-d');
        $worktimes = Worktime::where('date', $current_date)
                ->orderBy('date', 'asc')
                ->whereNotNull('end_time')
                ->get();
                
        return view('admin.admin-attendance-list', compact('worktimes'));
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

    public function getApplicationList()
    {
        $worktimes = Worktime::all();
        return view('application', compact('worktimes'));
    }
}
