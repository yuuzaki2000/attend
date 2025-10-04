<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Status;
use App\Models\Worktime;
use App\Models\Breaktime;

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
        $breaktime->save();
        $breaktimeId = $breaktime->id;

        $worktime_data = [
            'breaktime_id' => $breaktimeId,
        ];

        Worktime::find($request->worktimeId)->update($worktime_data);

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
        $current_time = Carbon::now()->format('H:i');
        $current_date = Carbon::now()->format('Y-m-d');

        $worktimes = Worktime::where('user_id', Auth::user()->id)
                    ->whereYear('date', Carbon::now()->year)
                    ->whereMonth('date', Carbon::now()->month)
                    ->orderBy('date', 'asc')
                    ->whereNotNull('end_time')
                    ->whereNotNull('breaktime_id')
                    ->get();

        return view('attendance-list', compact('worktimes'));
    }

    public function getDetail(){
        return view('attendance-detail');
    }
}
