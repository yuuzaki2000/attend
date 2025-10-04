@extends('layouts.admin-app')

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
                <th class="attendance-header">名前</th>
                <th class="attendance-header">開始時間</th>
                <th class="attendance-header">退勤時間</th>
                <th class="attendance-header">休憩時間</th>
                <th class="attendance-header">詳細</th>
            </tr>
            @foreach ($worktimes as $worktime)
            <tr>
                <td class="attendance-data">{{$worktime->date}}</td>
                <td class="attendance-data">火</td>
                <td class="attendance-data">水</td>
                <td class="attendance-data">木</td>
                @php
                $breakStartTime = \Carbon\Carbon::parse(optional($worktime->breaktime)->start_time);
                $breakEndTime = \Carbon\Carbon::parse(optional($worktime->breaktime)->end_time);
                $diffBreakHourTime = $breakStartTime->diffInHours($breakEndTime);
                $diffBreakMinuteTime = $breakStartTime->diffinMinutes($breakEndTime) % 60;
                @endphp
                <td class="attendance-data">{{$diffBreakHourTime}}:{{$diffBreakMinuteTime}}</td>
                <td class="attendance-data"></td>
            </tr>
            @endforeach
        </table>
    </div>
@endsection