@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{asset('css/attendance_list.css')}}">
@endsection

@section('content')
    <div class="month-select-container">
        <div><button>前月{{\Carbon\Carbon::now()->subMonth()->format('Y年m月')}}</button></div>
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
            @foreach ($worktimes as $worktime)
            <tr>
                @php
                $workStartTime = \Carbon\Carbon::parse($worktime->start_time);
                $workEndTime = \Carbon\Carbon::parse($worktime->end_time);
                $totalBreakTimeInterval = \Carbon\CarbonInterval::hours(0)->minutes(0);

                if(count($worktime->breaktimes) !== 0){
                    foreach($worktime->breaktimes as $breaktime){
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
                @endphp
                <td class="attendance-data">{{$worktime->date}}</td>
                <td class="attendance-data">{{$workStartTime->format("H:i")}}</td>
                <td class="attendance-data">{{$workEndTime->format("H:i")}}</td>
                <td class="attendance-data">{{$totalBreakTimeInterval->format('%h:%i')}}</td>
                <td class="attendance-data">{{$combined->format('%h:%i')}}</td>
                <td class="attendance-data">
                    <form action="/attendance/detail/{{$worktime->id}}" method="get">
                    @csrf
                        <button type="submit">詳細</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </table>
    </div>
@endsection