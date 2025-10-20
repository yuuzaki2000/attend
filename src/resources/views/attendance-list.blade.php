@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{asset('css/attendance_list.css')}}">
@endsection

@section('content')
    <div class="month-select-container">
        <form action="/attendance/list" method="post">
            @csrf
            <input type="hidden" name="standardDay" value="2025-09-15 22:52:00">
            <button type="submit">前月{{\Carbon\Carbon::now()->subMonth()->format('Y年m月')}}</button>
        </form>
        <div class="month-select-bar">{{\Carbon\Carbon::now()->format('Y年m月')}}</div>
        <div><button>翌月</button></div>
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
            @foreach ($days as $day)
            <tr>
                @php
                $worktime = App\Models\Worktime::where('date', $day->format('Y-m-d'))->first();
                if($worktime){
                    $startTime = $worktime->start_time;
                    $endTime = $worktime->end_time;
                }else{
                    $startTime = null;
                    $endTime = null;
                }
                //totalBreakTimeIntervalの計算
                $totalBreakTimeInterval = \Carbon\CarbonInterval::hours(0)->minutes(0);

                if($worktime){
                    $breaktimes = App\Models\Breaktime::where('worktime_id', $worktime->id)->get();

                    foreach($breaktimes as $breaktime){
                        $breakStartTime = \Carbon\Carbon::create($breaktime->start_time);
                        $breakEndTime = \Carbon\Carbon::create($breaktime->end_time);
                        $breakTimeInterval = $breakStartTime->diff($breakEndTime);
                        $totalBreakTimeInterval->add($breakTimeInterval);
                    }
                }else{
                }

                if($worktime){
                    $workStartTime = \Carbon\Carbon::create($worktime->start_time);
                    $workEndTime = \Carbon\Carbon::create($worktime->end_time);
                    $workTimeInterval = \Carbon\CarbonInterval::instance($workStartTime->diff($workEndTime));
                    $attendanceTimeInterval = $workTimeInterval->subtract($totalBreakTimeInterval);
                }else{
                    $attendanceTimeInterval = \Carbon\CarbonInterval::hours(0)->minutes(0);;
                }

                //配列の書き方で、breaktimeのデータを検索する
                @endphp
                <td class="attendance-data">{{$day->format('Y/m/d')}}</td>
                <td class="attendance-data">{{$startTime}}</td>
                <td class="attendance-data">{{$endTime}}</td>
                <td class="attendance-data">{{$totalBreakTimeInterval->format('%h:%i')}}</td>
                <td class="attendance-data">{{$attendanceTimeInterval->format('%h:%i')}}</td>
                @if($worktime)
                <td class="attendance-data">
                    <form action="/attendance/detail/{{$worktime->id}}" method="get">
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
@endsection