@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{asset('css/attendance_list.css')}}">
@endsection

@section('content')
    <div class="month-select-container">
        <div class="month-select-bar">2025年10月</div>
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
                $diffWorkHourTime = $workStartTime->diffInHours($workEndTime);
                $diffWorkMinuteTime = $workStartTime->diffinMinutes($workEndTime) % 60;
                $breakStartTime = \Carbon\Carbon::parse(optional($worktime->breaktime)->start_time);
                $breakEndTime = \Carbon\Carbon::parse(optional($worktime->breaktime)->end_time);
                $diffBreakHourTime = $breakStartTime->diffInHours($breakEndTime);
                $diffBreakMinuteTime = $breakStartTime->diffinMinutes($breakEndTime) % 60;
                $workTime = \Carbon\Carbon::parse($diffWorkHourTime . ":" . $diffWorkMinuteTime . ":00");
                $breakTime = \Carbon\Carbon::parse($diffBreakHourTime . ":" . $diffBreakMinuteTime . ":00");
                $diffTotalHourTime = $breakTime->diffInHours($workTime);
                $diffTotalMinuteTime = $breakTime->diffInMinutes($workTime) % 60;
                $totalTime = \Carbon\Carbon::parse($diffTotalHourTime . ":" . $diffTotalMinuteTime . ":00");
                @endphp
                <td class="attendance-data">{{$worktime->date}}</td>
                <td class="attendance-data">{{$workStartTime->format("H:i")}}</td>
                <td class="attendance-data">{{$workEndTime->format("H:i")}}</td>
                <td class="attendance-data">{{$breakTime->format("H:i")}}</td>
                <td class="attendance-data">{{$totalTime->format("H:i")}}</td>
                <td class="attendance-data"></td>
            </tr>
            @endforeach
        </table>
    </div>
@endsection