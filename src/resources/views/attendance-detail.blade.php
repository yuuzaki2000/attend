@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{asset('css/attendance_detail.css')}}">
@endsection
@section('title')
<div class="inner-title">❚ 勤怠詳細</div>
@endsection
@section('content')
    <form class="container" action="/attendance/detail/{{$worktime->id}}" method="post">
    @csrf
        <div class="table-container">
            <table>
                <tr class="table-row">
                    <th class="table-header">名前</th>
                    <td class="table-detail">{{$worktime->user->name}}</td>
                </tr>
                <tr class="table-row">
                    <th class="table-header">日付</th>
                    @php
                        $year = \Carbon\Carbon::create($worktime->date)->year;
                        $month = \Carbon\Carbon::create($worktime->date)->month;
                        $day = \Carbon\Carbon::create($worktime->date)->day;
                    @endphp
                    <td class="table-detail">{{$year}}年</td>
                    <td class="table-detail">{{$month}}月</td>
                    <td class="table-detail">{{$day}}日</td>
                </tr>
                <tr class="table-row">
                    <th class="table-header">出勤・退勤</th>
                    <td class="table-detail"><input class="input" type="text" name="workStartTime" value={{\Carbon\Carbon::create($worktime->start_time)->format('H:i')}}></td>
                    <td class="table-detail">～</td>
                    <td class="table-detail"><input class="input" type="text" name="workEndTime" value={{\Carbon\Carbon::create($worktime->end_time)->format('H:i')}}></td>
                </tr>
                @if (count($worktime->breaktimes) > 0)
                @foreach ($worktime->breaktimes as $key => $value)
                <tr class="table-row">
                    <th class="table-header">休憩{{$key}}</th>
                    <td class="table-detail"><input class="input" type="text" name="breakStartTime[{{$key}}]" value="{{\Carbon\Carbon::create($value->start_time)->format('H:i')}}"></td>
                    <td class="table-detail">～</td>
                    <td class="table-detail"><input class="input" type="text" name="breakEndTime[{{$key}}]" value="{{\Carbon\Carbon::create($value->end_time)->format('H:i')}}"></td>
                </tr>
                @error("breakStartTime.$key")
                    <tr>
                        <td colspan="4">{{$message}}</td>
                    </tr>                    
                @enderror
                @error("breakEndTime.$key")
                    <tr>
                        <td colspan="4">{{$message}}</td>
                    </tr>                    
                @enderror
                @endforeach
                @endif
                <tr class="table-row">
                    <th class="table-header">備考</th>
                    <td class="table-detail" colspan="3">
                        <input type="text" name="remarks" value="{{$worktime->remarks}}">
                    </td>
                </tr>
                @error('remarks')
                    <tr>
                        <td colspan="4">{{$message}}</td>
                    </tr>
                @enderror
            </table>
        </div>
        <div>
            <button class="update-button" style="background-color: #000; color:#FFF">修正</button>
        </div>
    </form>
@endsection