@extends('layouts.admin-app')

@section('css')
<link rel="stylesheet" href="{{asset('css/admin_approval.css')}}">
@endsection
@section('title')
❚　勤怠詳細
@endsection
@section('content')
    @php
        
    @endphp
    <form class="table-container" action="/stamp_correction_request/approve/{{$worktime->id}}" method="post">
    @csrf
            <input type="hidden" name="worktimeId" value="{{$worktime->id}}">
            <table>
                <input type="hidden" name="tempWorktimeId" value="{{$tempWorktime->id}}">
                <tr class="table-row">
                    <th class="table-header">名前</th>
                    <td class="table-data">{{$tempWorktime->user->name}}</td>
                </tr>
                <tr class="table-row">
                    <th class="table-header">日付</th>
                    @php
                        $year = \Carbon\Carbon::create($tempWorktime->date)->year;
                        $month = \Carbon\Carbon::create($tempWorktime->date)->month;
                        $day = \Carbon\Carbon::create($tempWorktime->date)->day;
                    @endphp
                    <td class="table-data">{{$year}}年</td>
                    <td class="table-data">{{$month}}月</td>
                    <td class="table-data">{{$day}}日</td>
                </tr>
                <tr class="table-row">
                    <th class="table-header">出勤・退勤</th>
                    <td class="table-data"><input class="input" type="text" name="workStartTime" value={{\Carbon\Carbon::create($tempWorktime->start_time)->format('H:i')}}></td>
                    <td class="table-data">～</td>
                    <td class="table-data"><input class="input" type="text" name="workEndTime" value={{\Carbon\Carbon::create($tempWorktime->end_time)->format('H:i')}}></td>
                </tr>
                @if (count($tempWorktime->tempBreaktimes) > 0)
                @foreach ($tempWorktime->tempBreaktimes as $tempBreaktime)
                <tr class="table-row">
                    <th class="table-header">休憩</th>
                    <td class="table-data"><input class="input" type="text" name="breakStartTime[]" value={{$tempBreaktime->start_time}}></td>
                    <td class="table-data">～</td>
                    <td class="table-data"><input class="input" type="text" name="breakEndTime[]" value={{$tempBreaktime->end_time}}></td>
                </tr>
                @endforeach
                @endif
                <tr class="table-row">
                    <th class="table-header">備考</th>
                    <td class="table-data" colspan="3"></td>
                </tr>
            </table>
            <div>
                <button type="submit" style="background-color: #000; color:#FFF">承認</button>
            </div>        
    </form>
@endsection