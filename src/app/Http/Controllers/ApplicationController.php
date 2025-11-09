<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Worktime;
use Illuminate\Support\Collection;
use App\Models\Application;

class ApplicationController extends Controller
{
    //
    public function getApplicationList(Request $request)
    {
        if(Auth::guard('web')->check()){
            if($request->page == "approved"){
                $worktimes = Worktime::where('user_id', Auth::user()->id)
                                ->whereHas('application.approval', function($query){
                                    $query->where('is_approved',1);
                                })->get();
                $appliedWorktimes = new Collection();

                foreach($worktimes as $worktime){
                    if($worktime->application !== null){
                        $application = Application::where('worktime_id', $worktime->id)
                                        ->orderBy('created_at', 'desc')
                                        ->first();
                        $particularWorktime = Worktime::find($application->worktime_id);
                        $appliedWorktimes->push($particularWorktime);
                    }
                }

            }else{
                $worktimes = Worktime::where('user_id', Auth::user()->id)
                                ->whereHas('application.approval', function($query){
                                    $query->where('is_approved',0);
                                })->get();

                $appliedWorktimes = new Collection();

                foreach($worktimes as $worktime){
                    if($worktime->application !== null){
                        $application = Application::where('worktime_id', $worktime->id)
                                        ->orderBy('created_at', 'desc')
                                        ->first();
                        $particularWorktime = Worktime::find($application->worktime_id);
                        $appliedWorktimes->push($particularWorktime);
                    }
                }
            }

            return view('application-list', compact('appliedWorktimes'));

        }else if(Auth::guard('admin')->check()){
                if($request->page == "approved"){
                $worktimes = Worktime::whereHas('application.approval', function($query){
                                    $query->where('is_approved',1);
                                })->get();
                $appliedWorktimes = new Collection();

                foreach($worktimes as $worktime){
                    if($worktime->application !== null){
                        $application = Application::where('worktime_id', $worktime->id)
                                        ->orderBy('created_at', 'desc')
                                        ->first();
                        $particularWorktime = Worktime::find($application->worktime_id);
                        $appliedWorktimes->push($particularWorktime);
                    }
                }

            }else{
                $worktimes = Worktime::whereHas('application.approval', function($query){
                                    $query->where('is_approved',0);
                                })->get();

                $appliedWorktimes = new Collection();

                foreach($worktimes as $worktime){
                    if($worktime->application !== null){
                        $application = Application::where('worktime_id', $worktime->id)
                                        ->orderBy('created_at', 'desc')
                                        ->first();
                        $particularWorktime = Worktime::find($application->worktime_id);
                        $appliedWorktimes->push($particularWorktime);
                    }
                }
            }

            return view('admin.admin-application-list', compact('appliedWorktimes'));

        }else{
            return redirect()->route('login');
        }
    }
}
