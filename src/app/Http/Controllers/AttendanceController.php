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
    public function index(){
        $current_time = Carbon::now()->format('H:i');
        $current_date = Carbon::now()->format('Y-m-d');
        $status = Status::where('user_id', Auth::user()->id)->first();
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
                return view('attendance-off', compact('current_time', 'current_date'));
                break;
            case "勤務中":
                return view('attendance-atd', compact('current_time', 'current_date'));
                break;
            case "休憩中":
                return view('attendance-brk', compact('current_time', 'current_date'));
                break;
            case "退勤済":
                return view('attendance-end', compact('current_time', 'current_date'));
                break;
            default:
                return view('attendance-off', compact('current_time', 'current_date'));
                break;
        }

    }


    public function update(){
        $current_time = Carbon::now()->format('H:i');
        $current_date = Carbon::now()->format('Y-m-d');
        $status_atd = Status::where('user_id', Auth::user()->id)->first()->content;

        switch($status_atd){
            case "勤務外":
                $status_data = [
                    'user_id' => Auth::user()->id,
                    'date' => $current_date,
                    'content' => '勤務中',
                ];

                $worktime_data = [
                    'date' => $current_date,
                    'user_id' => Auth::user()->id,
                    'start_time' => $current_time,
                    'end_time' => null,
                    'breaktime_id' => null,
                ];

                Status::where('user_id', Auth::user()->id)->update($status_data);
                Worktime::create($worktime_data);
                break;
            case "勤務中":
                //処理
                $worktime_data = [
                    'end_time' => $current_time,
                ];

                $status_data = [
                    'content' => '退勤済',
                ];

                Worktime::where('user_id', Auth::user()->id)->update($worktime_data);
                Status::where('user_id', Auth::user()->id)->update($status_data);
                break;
            case "休憩中":
                //処理
                break;
            case "退勤済":
                //処理
                break;
        }

        return redirect()->route('guest.attendance.index', compact('current_time', 'current_date'));
    }

    public function takeBreak(){
        $current_time = Carbon::now()->format('H:i');
        $current_date = Carbon::now()->format('Y-m-d');
        $breaktime_data = [
            'start_time' => $current_time,
            'end_time' => null,
        ];

        $status_data = [
            'content' => '休憩中',
        ];
        Breaktime::create($breaktime_data);
        Status::where('user_id', Auth::user()->id)->update($status_data);
        return redirect()->route('guest.attendance.index', compact('current_time', 'current_date'));
    }

    public function leaveBreak(){
        $current_time = Carbon::now()->format('H:i');
        $current_date = Carbon::now()->format('Y-m-d');
        $breaktime_data = [
            'end_time' => $current_time,
        ];

        $status_data = [
            'content' => '勤務中',
        ];

        Worktime::where('user_id', Auth::user()->id)->first()->breaktime;
        Status::where('user_id', Auth::user()->id)->update($status_data);
        return redirect()->route('guest.attendance.index', compact('current_time', 'current_date'));
    }

    public function getList(){
        return view('attendance-list');
    }
}
