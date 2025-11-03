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
use Illuminate\Support\Collection;
use App\Models\Approval;
use Illuminate\Support\Facades\DB;





class AttendanceController extends Controller
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

    public function getPreviousMonthList(Request $request){
        $particularDate = Carbon::parse($request->monthPreviousParticularDate);

        $dates = $this->getDatesOfMonth($particularDate->year,$particularDate->month);

        $revisedWorktimeArray = collect();

        foreach($dates as $date){
            $worktime = Worktime::where('user_id', Auth::id())
                    ->where('date', $date->format('Y-m-d'))
                    ->first();
            if($worktime !== null){
                $revisedWorktimeArray->push($worktime->attributesToArray());
            }else{
                $revisedWorktime = [
                    'id' => null,
                    'user_id' => Auth::id(),
                    'date' => $date->format('Y-m-d'),
                    'start_time' => null,
                    'end_time' => null,
                    'created_at' => null,
                    'updated_at' => null,
                ];
                $revisedWorktimeArray->push($revisedWorktime);
            }
        }

        return view('attendance-list', compact('particularDate', 'revisedWorktimeArray','dates'));
    }

    public function getLaterMonthList(Request $request){
        $particularDate = Carbon::parse($request->monthLaterParticularDate);

        $dates = $this->getDatesOfMonth($particularDate->year,$particularDate->month);

        $revisedWorktimeArray = collect();

        foreach($dates as $date){
            $worktime = Worktime::where('user_id', Auth::id())
                    ->where('date', $date->format('Y-m-d'))
                    ->first();
            if($worktime !== null){
                $revisedWorktimeArray->push($worktime->attributesToArray());
            }else{
                $revisedWorktime = [
                    'id' => null,
                    'user_id' => Auth::id(),
                    'date' => $date->format('Y-m-d'),
                    'start_time' => null,
                    'end_time' => null,
                    'created_at' => null,
                    'updated_at' => null,
                ];
                $revisedWorktimeArray->push($revisedWorktime);
            }
        }

        return view('attendance-list', compact('particularDate', 'revisedWorktimeArray', 'dates'));
    }

    public function getList(){
        $particularDate = Carbon::now();

        $dates = $this->getDatesOfMonth($particularDate->year,$particularDate->month);

        return view('attendance-list', compact('dates', 'particularDate'));
    }

    public function getDetail($id){
        $current_time = Carbon::now()->format('H:i');
        $current_date = Carbon::now()->format('Y-m-d');
        $worktime = Worktime::find($id);
        return view('attendance-detail', compact('worktime'));
    }
    //
    public function update(Request $request, $id){


        try{
            DB::beginTransaction();

            $worktime = Worktime::find($id);
            $temp_worktime = new TempWorktime();
            $temp_worktime->date = $worktime->date;
            $temp_worktime->user_id = Auth::user()->id;
            $temp_worktime->start_time = $request->workStartTime;
            $temp_worktime->end_time = $request->workEndTime;
            $temp_worktime->save();
            $tempWorktimeId = $temp_worktime->id;

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

            $application = new Application();
            $application->worktime_id = $id;
            $application->temp_worktime_id = $tempWorktimeId;
            $application->reason = $request->reason;
            $application->save();
            $applicationId = $application->id;

            $approval = new Approval();
            $approval->application_id = $applicationId;
            $approval->is_approved = false;
            $approval->save();

            DB::commit();
            return redirect('/attendance/list');
        }catch(Exception $e){
            DB::rollback();
            return redirect()->back()->with('error', "エラーが発生しました");
        }
    }
}
