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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Carbon\CarbonInterval;


class AdminAttendanceController extends Controller
{
    //
    private function getDatesOfMonth($year, $month){
        $dates = [];
        $particularDate = Carbon::create($year, $month, 1);
        $numberOfDays = $particularDate->daysInMonth;
        for($i=0; $i<$numberOfDays; $i++){
            $date = $particularDate->copy()->startOfMonth()->addDays($i);
            $dates[] = $date;
        }
        return $dates;
    }

    public function getList(){
        $particularDate = Carbon::now();

        $worktimes = Worktime::where('date', $particularDate->format('Y-m-d'))->get();

        $users = User::all();
                
        return view('admin.admin-attendance-list', compact('worktimes', 'particularDate', 'users'));
    }

    public function getPreviousDateList(Request $request){
        $particularDate = Carbon::parse($request->previousParticularDate);
        $worktimes = Worktime::where('date', $particularDate->format('Y-m-d'))->get();
        $users = User::all();

        return view('admin.admin-attendance-list', compact('worktimes','particularDate','users'));
    }

    public function getLaterDateList(Request $request){
        $particularDate = Carbon::parse($request->laterParticularDate);
        $worktimes = Worktime::where('date', $particularDate->format('Y-m-d'))->get();
        $users = User::all();

        return view('admin.admin-attendance-list', compact('worktimes','particularDate', 'users'));
    }

    public function getDetail($id){
        $current_time = Carbon::now()->format('H:i');
        $current_date = Carbon::now()->format('Y-m-d');
        $worktime = Worktime::find($id);
        return view('admin.admin-attendance-detail', compact('worktime'));
    }

    public function update(AttendanceRequest $request, $id){
        
        try{
            DB::beginTransaction();

            $worktime = Worktime::find($id);
            $worktime->update([
                'start_time' => $request->workStartTime,
                'end_time' => $request->workEndTime,
                'remarks' => $request->remarks,
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

    public function approve(Request $request, $attendance_correct_request){
        $worktime = Worktime::find($attendance_correct_request);
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
        $particularDate = Carbon::now();
        $userId = $id;
        $dates = $this->getDatesOfMonth($particularDate->year,$particularDate->month);
        return view('admin.staff-attendance-list', compact('userId', 'dates', 'particularDate'));
    }

    public function getPreviousMonthList(Request $request){
        $particularDate = Carbon::parse($request->monthPreviousParticularDate);
        $dates = $this->getDatesOfMonth($particularDate->year,$particularDate->month);
        $userId = $request->userId;
        return view('admin.staff-attendance-list', compact('userId', 'dates', 'particularDate'));
    }

    public function getLaterMonthList(Request $request){
        $particularDate = Carbon::parse($request->monthLaterParticularDate);
        $dates = $this->getDatesOfMonth($particularDate->year,$particularDate->month);
        $userId = $request->userId;
        return view('admin.staff-attendance-list', compact('userId', 'dates', 'particularDate'));
    }

    public function export(Request $request){

        $particularDate = Carbon::parse($request->particularDate);
        $userId = $request->particularUserId;
        $dates = $this->getDatesOfMonth($particularDate->year,$particularDate->month);

        $stream = fopen('php://temp', 'w');
        $arr = array('日付', '開始時間','退勤時間','休憩時間', '合計');
        fputcsv($stream, $arr);
        foreach($dates as $date){

            //totalBreakTimeIntervalの計算
                    $worktime = Worktime::where('date', $date->format('Y-m-d'))
                                ->where('user_id', $userId)
                                ->first();
                    $totalBreakTimeInterval = CarbonInterval::hours(0)->minutes(0);

                    if($worktime !== null){
                        $breaktimes = Breaktime::where('worktime_id', $worktime->id)->get();

                        foreach($breaktimes as $breaktime){
                            $breakStartTime = Carbon::create($breaktime->start_time);
                            $breakEndTime = Carbon::create($breaktime->end_time);
                            $breakTimeInterval = $breakStartTime->diff($breakEndTime);
                            $totalBreakTimeInterval->add($breakTimeInterval);
                        }

                        $workStartTime = Carbon::create($worktime->start_time);
                        $workEndTime = Carbon::create($worktime->end_time);
                        $workTimeInterval = CarbonInterval::instance($workStartTime->diff($workEndTime));
                        $attendanceTimeInterval = $workTimeInterval->subtract($totalBreakTimeInterval);
                    }else{
                        $workStartTime = null;
                        $workEndTime = null;
                        $totalBreakTimeInterval = CarbonInterval::hours(0)->minutes(0);
                        $attendanceTimeInterval = CarbonInterval::hours(0)->minutes(0);
                    }

            $arrInfo = array(
                'date' => $date->format('Y-m-d'),
                'workStartTime' => $workStartTime?$workStartTime->format('H:i'):'0:00',
                'workEndTime' => $workEndTime?$workEndTime->format('H:i'):'0:00',
                'breakTimeInterval' => $totalBreakTimeInterval->format('%h:%i'),
                'workTotalTime' => $attendanceTimeInterval->format('%h:%i'),
            );
            fputcsv($stream, $arrInfo);
        }
        
        rewind($stream);
        $csv = stream_get_contents($stream);
        $csv = mb_convert_encoding($csv, 'sjis-win', 'UTF-8');
        fclose($stream);

        $headers = array(
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="test.csv"',
        );

        return Response::make($csv, 200, $headers);
    }
}