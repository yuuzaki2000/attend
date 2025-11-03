@extends('layouts.admin-app')

@section('css')
<link rel="stylesheet" href="{{asset('css/attendance_detail.css')}}">
@endsection
@section('title')
❚　勤怠詳細
@endsection
@section('content')
    <form class="table-container" action="/admin/attendance/{{$worktime->id}}" method="post">
    @csrf
            <table>
                <tr class="table-row">
                    <th class="table-header">名前</th>
                    <td class="table-data">{{$worktime->user->name}}</td>
                </tr>
                <tr class="table-row">
                    <th class="table-header">日付</th>
                    @php
                        $year = \Carbon\Carbon::create($worktime->date)->year;
                        $month = \Carbon\Carbon::create($worktime->date)->month;
                        $day = \Carbon\Carbon::create($worktime->date)->day;
                    @endphp
                    <td class="table-data">{{$year}}年</td>
                    <td class="table-data">{{$month}}月</td>
                    <td class="table-data">{{$day}}日</td>
                </tr>
                <tr class="table-row">
                    <th class="table-header">出勤・退勤</th>
                    <td class="table-data"><input class="input" type="text" name="workStartTime" value={{\Carbon\Carbon::create($worktime->start_time)->format('H:i')}}></td>
                    <td class="table-data">～</td>
                    <td class="table-data"><input class="input" type="text" name="workEndTime" value={{\Carbon\Carbon::create($worktime->end_time)->format('H:i')}}></td>
                </tr>
                @error('workStartTime')
                <tr>
                    <td>
                        <p>{{$errors->first('workStartTime')}}</p>
                    </td>
                </tr>
                @enderror
                @error('workStartWorkEndContext')
                <tr>
                    <td>
                        <p>{{$errors->first('workStartWorkEndTemporalOrder')}}</p>
                    </td>
                </tr>
                @enderror
                    
                @php
                    $breaktime = $worktime->breaktimes[0];
                @endphp
                <tr class="table-row">
                    <th class="table-header">休憩</th>
                    <td class="table-data"><input class="input" type="text" name="breakStartTime" value={{\Carbon\Carbon::create($breaktime->start_time)->format('H:i')}}></td>
                    <td class="table-data">～</td>
                    <td class="table-data"><input class="input" type="text" name="breakEndTime" value={{\Carbon\Carbon::create($breaktime->end_time)->format('H:i')}}></td>
                </tr>
                <tr class="table-row">
                    <th class="table-header">備考</th>
                    <td class="table-data" colspan="3"></td>
                </tr>
            </table>
            <div>
                <button type="submit">修正</button>
            </div>        
    </form>
@endsection

{{--
        dd($worktime->breaktimes[0]);
--}}