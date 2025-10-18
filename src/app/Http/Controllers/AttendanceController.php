<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Status;
use App\Models\Worktime;
use App\Models\Breaktime;
use App\Models\TempWorktime;
use App\Models\Application;
use App\Models\TempBreaktime;



class AttendanceController extends Controller
{
    //

    public function index(Request $request){
        $current_time = Carbon::now()->format('H:i');
        $current_date = Carbon::now()->format('Y-m-d');
        $status = Status::where('user_id', Auth::user()->id)->where('date', $current_date)->first();
        if($status == null){
            $status_data = [
                'user_id' => Auth::user()->id,
                'date' => $current_date,
                'content' => '勤務外',
            ];
            Status::create($status_data);
            $status_atd = '勤務外';
        }else{
            $status_atd = $status->content;
        }

        switch($status_atd){
            case "勤務外":
                $data = [
                    'current_time' => $current_time,
                    'current_date' => $current_date,
                ];
                return view('attendance-off', $data);
                break;
            case "勤務中":
                $worktimeId = Worktime::where('user_id', Auth::user()->id)->where('date', $current_date)->first()->id;
                if($request->worktimeId !== null){
                    $data = [
                        'current_time' => $current_time,
                        'current_date' => $current_date,
                        'worktimeId' => $request->worktimeId,
                    ];
                }else{
                    $data = [
                        'current_time' => $current_time,
                        'current_date' => $current_date,
                        'worktimeId' => $worktimeId,
                    ];
                }

                return view('attendance-atd', $data);
                break;
            case "休憩中":
                    $data = [
                        'current_time' => $current_time,
                        'current_date' => $current_date,
                        'breaktimeId' => $request->breaktimeId,
                    ]; 
                    return view('attendance-brk', $data);
                    break;
            case "退勤済":
                    $data = [
                        'current_time' => $current_time,
                        'current_date' => $current_date,
                    ];
                    return view('attendance-end', $data);
            default:
                $data = [
                    'current_time' => $current_time,
                    'current_date' => $current_date,
                    'breaktimeId' => null,
                ];
                return view('attendance', $data);
                break;
        }
    }
    
    public function startWork(){
        $current_time = Carbon::now()->format('H:i');
        $current_date = Carbon::now()->format('Y-m-d');

        $worktime = new Worktime();
        $worktime->date = $current_date;
        $worktime->user_id = Auth::user()->id;
        $worktime->start_time = $current_time;
        $worktime->end_time = null;
        $worktime->save();
        $worktimeId = $worktime->id;

        $data = [
            'current_time' => $current_time,
            'current_date' => $current_date,
            'worktimeId' => $worktimeId,
        ];

        $status_data = [
            'content' => '勤務中',
        ];

        Status::where('user_id', Auth::user()->id)->update($status_data);

        return redirect()->route('guest.attendance.index', $data);
    }

    public function endWork(Request $request){
        $current_time = Carbon::now()->format('H:i');
        $current_date = Carbon::now()->format('Y-m-d');
        $worktime_data = [
            'end_time' => $current_time,
        ];

        $status_data = [
            'content' => "退勤済"
        ];

        $data = [
                    'current_time' => $current_time,
                    'current_date' => $current_date,
        ];

        Worktime::find($request->worktimeId)->update($worktime_data);
        Status::where('user_id', Auth::user()->id)->update($status_data);
        return redirect()->route('guest.attendance.index', $data);
    }

    public function takeBreak(Request $request){
        $current_time = Carbon::now()->format('H:i');
        $current_date = Carbon::now()->format('Y-m-d');

        $breaktime = new Breaktime();
        $breaktime->start_time = $current_time;
        $breaktime->end_time = null;
        $breaktime->worktime_id = $request->worktimeId;
        $breaktime->save();
        $breaktimeId = $breaktime->id;

        $data = [
            'current_time' => $current_time,
            'current_date' => $current_date,
            'breaktimeId' => $breaktimeId,
        ];

        $status_data = [
            'content' => '休憩中',
        ];

        Status::where('user_id', Auth::user()->id)->update($status_data);

        return redirect()->route('guest.attendance.index', $data);
    }

    public function leaveBreak(Request $request){
        $current_time = Carbon::now()->format('H:i');
        $current_date = Carbon::now()->format('Y-m-d');
        $worktimeId = Worktime::where('user_id', Auth::user()->id)->where('date', $current_date)->first()->id;

        $breaktime_data = [
            'end_time' => $current_time,
        ];

        $status_data = [
            'content' => '勤務中',
        ];

        $data = [
            'current_time' => $current_time,
            'current_date' => $current_date,
            'worktimeId' => $worktimeId,
        ];

        Breaktime::find($request->breaktimeId)->update($breaktime_data);
        Status::where('user_id', Auth::user()->id)->update($status_data);
        return redirect()->route('guest.attendance.index', $data);
    }

    public function getList(){
        $sameYearAndMonthFullWorktimes = Worktime::where('user_id', Auth::user()->id)
                    ->whereYear('date', Carbon::now()->year)
                    ->whereMonth('date', Carbon::now()->month)
                    ->whereNotNull('end_time')
                    ->get();

        $numberOfDays = Carbon::now()->daysInMonth;

        $days = [];
        for($i = 0; $i<$numberOfDays; $i++){
            $day = Carbon::now()->startOfMonth()->addDays($i);
            $days[] = $day;
        }

        return view('attendance-list', compact('days'));
    }

    public function getPreviousMonthList(Request $request){
        $standardDay = $request->standardDay;
        $standardDayTime = Carbon::parse($standardDay);
        $numberOfDays = $standardDayTime->daysInMonth;

        $days = [];
        for($i=0; $i<$numberOfDays; $i++){
            $day = $standardDayTime->startOfMonth()->addDays($i);
            $days[] = $day;
        };

        $worktimes = collect();

        foreach($days as $day){
            $worktime = Worktime::where('user_id', Auth::id())
                    ->where('date', $day->format('Y-m-d'))
                    ->first();
            if($worktime !== null){
                $worktimes->push($worktime);
            }else{
                $revisedWorktime = [
                    'user_id' => Auth::id(),
                    'date' => $day->format('Y-m-d'),
                    'start_time' => null,
                    'end_time' => null,
                    'created_at' => null,
                    'updated_at' => null,
                ];
                $worktimes->push($revisedWorktime);
            }
        }

        return redirect('/attendance/list');
    }

    public function getDetail($id){
        $current_time = Carbon::now()->format('H:i');
        $current_date = Carbon::now()->format('Y-m-d');
        $worktime = Worktime::find($id);
        return view('attendance-detail', compact('worktime'));
    }
    //
    public function update(Request $request, $id){
        $worktime = Worktime::find($id);
        $temp_worktime = new TempWorktime();
        $temp_worktime->date = $worktime->date;
        $temp_worktime->user_id = Auth::user()->id;
        $temp_worktime->start_time = $request->workStartTime;
        $temp_worktime->end_time = $request->workEndTime;
        $temp_worktime->save();
        $tempWorktimeId = $temp_worktime->id;

        $application = new Application();
        $application->worktime_id = $id;
        $application->temp_worktime_id = $tempWorktimeId;
        $application->reason = $request->reason;
        $application->save();

        if(count($worktime->breaktimes) > 0){
            for($i=0; $i<count($worktime->breaktimes); $i++){
                $temp_breaktime = new TempBreaktime();
                $temp_breaktime->start_time = $request->breakStartTime[$i];
                $temp_breaktime->end_time = $request->breakEndTime[$i];
                $temp_breaktime->temp_worktime_id = $tempWorktimeId;
                $temp_breaktime->save();
            }
        }else{
        }
        
        return redirect('/attendance/list');
    }
}
