@extends('layouts.admin-app')

@section('css')
<link rel="stylesheet" href="{{asset('css/attendance_list.css')}}">
@endsection

@section('content')
    <div class="content">
        <form action="/admin/attendance/list/previous" method="get">
        @csrf
            <input type="hidden" name="previousParticularDate" value={{$particularDate->copy()->subDay()->toDateString()}}>
            <button type="submit">前日</button>
        </form>
        <div class="month-select-container">
            <div class="month-select-bar">{{$particularDate->format('Y年m月d日')}}</div>
        </div>
        <form action="/admin/attendance/list/later" method="get">
        @csrf
            <input type="hidden" name="laterParticularDate" value={{$particularDate->copy()->addDay()->toDateString()}}>
            <button type="submit">翌日</button>
        </form>
        <div class="attendance-table-container">
            <table class="attendance-table">
                <tr>
                    <th class="attendance-header">名前</th>
                    <th class="attendance-header">出勤</th>
                    <th class="attendance-header">退勤</th>
                    <th class="attendance-header">休憩</th>
                    <th class="attendance-header">合計</th>
                    <th class="attendance-header">詳細</th>
                </tr>
                @foreach ($users as $user)
                <tr>
                @php
                $eachWorktime = \App\Models\Worktime::where('user_id', $user->id)
                                ->where('date', $particularDate->format('Y-m-d'))->first();
                if($eachWorktime){
                    $workStartTime = \Carbon\Carbon::create($eachWorktime->start_time);
                        $workEndTime = \Carbon\Carbon::create($eachWorktime->end_time);
                        $totalBreakTimeInterval = \Carbon\CarbonInterval::hours(0)->minutes(0);

                        if(count($eachWorktime->breaktimes) !== 0){
                            foreach($eachWorktime->breaktimes as $breaktime){
                            $breakStartTime = \Carbon\Carbon::parse($breaktime->start_time);
                            $breakEndTime = \Carbon\Carbon::parse($breaktime->end_time);
                            //laravel8では、CarbonIntervalにならず、DateIntervalになることがあるので、instanceメソッドで補正
                            $breakTimeInterval = \Carbon\CarbonInterval::instance($breakStartTime->diff($breakEndTime));
                            $totalBreakTimeInterval->add($breakTimeInterval);
                            }
                        }else{
                        }

                    $workTimeInterval = \Carbon\CarbonInterval::instance($workStartTime->diff($workEndTime));
                    $attendanceTimeInterval = $workTimeInterval->totalMinutes - $totalBreakTimeInterval->totalMinutes;
                    $combined = \Carbon\CarbonInterval::minutes($attendanceTimeInterval)->cascade();

                }else{
                    $workStartTime = null;
                    $workEndTime = null;
                    $totalBreakTimeInterval = null;
                    $combined = null;
                }                
                @endphp
                    <td class="attendance-data">{{$user->name}}</td>
                    <td class="attendance-data">{{$workStartTime?$workStartTime->format("H:i"): null}}</td>
                    <td class="attendance-data">{{$workEndTime?$workEndTime->format("H:i"):null}}</td>
                    <td class="attendance-data">{{$totalBreakTimeInterval?$totalBreakTimeInterval->format('%h:%i'):null}}</td>
                    <td class="attendance-data">{{$combined?$combined->format('%h:%i'):null}}</td>
                    @if($eachWorktime)
                    <td class="attendance-data">
                        <form action="/admin/attendance/{{$eachWorktime->id}}" method="get">
                        @csrf
                            <button type="submit">詳細</button>
                        </form>
                    </td>
                    @else
                    <td class="attendance-data">詳細</td>
                    @endif
                </tr>
                @endforeach
            </table>
        </div>
    </div>
@endsection