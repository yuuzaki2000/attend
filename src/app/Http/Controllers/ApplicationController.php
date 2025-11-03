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
            if($request->page = "approved"){
                $worktimes = Worktime::where('user_id', Auth::user()->id)->get();
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
                $worktimes = Worktime::where('user_id', Auth::user()->id)->get();
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
                $worktimes = Worktime::all();
                $appliedWorktimes = new Collection();

                foreach($worktimes as $worktime){
                    if($worktime->application !== null){
                        $appliedWorktime = Worktime::whereHas('application.approval', function($query){
                            $query->where('is_approved', true);
                        })->first();
                        $appliedWorktimes->push($appliedWorktime);
                    }
                }

            /*
            $appliedWorktimes = Worktime::has('application')->whereHas('application.approval', function($query){
                $query->where('is_approved', false);
            });            */

            return view('admin.admin-application-list', compact('appliedWorktimes'));

        }else{
            return redirect()->route('login');
        }
    }
}
