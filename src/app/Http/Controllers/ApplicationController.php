<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Worktime;
use Illuminate\Support\Collection;

class ApplicationController extends Controller
{
    //
    public function getApplicationList()
    {
        if(Auth::guard('admin')->check()){
            $worktimes = Worktime::all();
            $appliedWorktimes = new Collection();

            foreach($worktimes as $worktime){
                if($worktime->application !== null){
                    $appliedWorktimes->push($worktime);
                }
            }
            return view('admin.admin-application-list', compact('appliedWorktimes'));

        }else if(Auth::guard('web')->check()){
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
            return view('application-list', compact('appliedWorktimes'));

        }else{
            return redirect()->route('login');
        }
    }
}
