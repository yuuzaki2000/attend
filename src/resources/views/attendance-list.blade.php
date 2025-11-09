@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{asset('css/attendance_list.css')}}">
@endsection

@section('content')
<div class="total-container">
    <div>attendance-list.blade.php</div>
    <div class="bar">
        <form class="previous" action="/attendance/list/previous" method="get">
        @csrf
            <input type="hidden" name="monthPreviousParticularDate" value={{$particularDate->copy()->subMonth()->toDateString()}}>
            <button type="submit">前月</button>
        </form>
        <div class="month-select-container">
            <div class="month-select-bar">{{$particularDate->format('Y年m月')}}</div>
        </div>
        <form class="later" action="/attendance/list/later" method="get">
        @csrf
            <input type="hidden" name="monthLaterParticularDate" value={{$particularDate->copy()->addMonth()->toDateString()}}>
            <button type="submit">翌月</button>
        </form>
    </div>
    <div class="attendance-table-container">
        <table class="attendance-table">
            <tr>
                <th class="attendance-header">日付</th>
                <th class="attendance-header">開始時間</th>
                <th class="attendance-header">退勤時間</th>
                <th class="attendance-header">休憩時間</th>
                <th class="attendance-header">合計</th>
                <th class="attendance-header">詳細</th>
            </tr>
            @foreach ($dates as $date)
            <tr>
                @php
                //totalBreakTimeIntervalの計算
                $worktime = \App\Models\Worktime::where('date', $date->format('Y-m-d'))
                            ->where('user_id', Auth::id())
                            ->first();
                $totalBreakTimeInterval = \Carbon\CarbonInterval::hours(0)->minutes(0);

                if($worktime !== null){
                    $breaktimes = App\Models\Breaktime::where('worktime_id', $worktime->id)->get();

                    foreach($breaktimes as $breaktime){
                        $breakStartTime = \Carbon\Carbon::create($breaktime->start_time);
                        $breakEndTime = \Carbon\Carbon::create($breaktime->end_time);
                        $breakTimeInterval = $breakStartTime->diff($breakEndTime);
                        $totalBreakTimeInterval->add($breakTimeInterval);
                    }

                    $workStartTime = \Carbon\Carbon::create($worktime->start_time);
                    $workEndTime = \Carbon\Carbon::create($worktime->end_time);
                    $workTimeInterval = \Carbon\CarbonInterval::instance($workStartTime->diff($workEndTime));
                    $attendanceTimeInterval = $workTimeInterval->subtract($totalBreakTimeInterval);
                }else{
                    $workStartTime = null;
                    $workEndTime = null;
                    $totalBreakTimeInterval = \Carbon\CarbonInterval::hours(0)->minutes(0);
                    $attendanceTimeInterval = \Carbon\CarbonInterval::hours(0)->minutes(0);
                }

                //配列の書き方で、breaktimeのデータを検索する
                @endphp
                <td class="attendance-detail">{{$date->format('Y-m-d')}}</td>
                <td class="attendance-detail">{{$workStartTime?$workStartTime->format('H:i'):null}}</td>
                <td class="attendance-detail">{{$workEndTime?$workEndTime->format('H:i'):null}}</td>
                <td class="attendance-detail">{{$totalBreakTimeInterval->format('%h:%i')}}</td>
                <td class="attendance-detail">{{$attendanceTimeInterval->format('%h:%i')}}</td>
                @if($worktime)
                <td class="attendance-detail">
                    <form action="/attendance/detail/{{$worktime->id}}" method="get">
                    @csrf
                        <button type="submit">詳細</button>
                    </form>
                </td>
                @else
                <td class="attendance-detail">詳細</td>
                @endif
            </tr>
            @endforeach
        </table>
    </div>
</div>
@endsection