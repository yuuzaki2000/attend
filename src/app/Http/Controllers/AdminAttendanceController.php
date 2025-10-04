<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Worktime;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AdminAttendanceController extends Controller
{
    //
    public function index(){
        $current_time = Carbon::now()->format('H:i');
        $current_date = Carbon::now()->format('Y-m-d');
        $worktimes = Worktime::where('date', $current_date)->get();
        return view('admin.admin-attendance-list', compact('worktimes'));
    }

    public function getDetail(){
        $current_time = Carbon::now()->format('H:i');
        $current_date = Carbon::now()->format('Y-m-d');
        $worktime = Worktime::where('user_id', Auth::user()->id)->where('date', $current_date)->first();
        return view('admin.admin-attendance-detail', compact('worktime'));
    }
}
