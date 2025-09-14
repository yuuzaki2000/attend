<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Status;

class AttendanceController extends Controller
{
    //
    public function index()
    {
        $current_time = Carbon::now()->format('H:i');
        $status = Status::where('user_id', Auth::user()->id)->first()->content;
        /*
        if($status =="勤務中"){
            return view('attendance-atd', compact('current_time'));
        }else{
            return view('attendance-end', compact('current_time'));
        }  */
        
        switch($status){
            case "勤務外":
                return view('attendance-off', compact('current_time'));
                break;
            case "勤務中":
                return view('attendance-atd', compact('current_time'));
                break;
            case "休憩中":
                return view('attendance-brk', compact('current_time'));
                break;
            case "退勤済":
                return view('attendance-end', compact('current_time'));
                break;
        }
    }
}
