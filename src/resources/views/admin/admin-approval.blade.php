@extends('layouts.admin-app')

@section('css')
<link rel="stylesheet" href="{{asset('css/admin_approval.css')}}">
@endsection
@section('title')
❚　勤怠詳細
@endsection
@section('content')
    <form class="table-container" action="" method="post">
    @csrf
            <div>admin-approval.blade.php</div>
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
                @if (count($worktime->breaktimes) > 0)
                @foreach ($worktime->breaktimes as $breaktime)
                <tr class="table-row">
                    <th class="table-header">休憩</th>
                    <td class="table-data"><input class="input" type="text" name="breakStartTime[]" value={{$breaktime->start_time}}></td>
                    <td class="table-data">～</td>
                    <td class="table-data"><input class="input" type="text" name="breakEndTime[]" value={{$breaktime->end_time}}></td>
                </tr>
                @endforeach
                @endif
                <tr class="table-row">
                    <th class="table-header">備考</th>
                    <td class="table-data" colspan="3"></td>
                </tr>
            </table>
            <div>
                <button type="submit">承認</button>
            </div>        
    </form>
@endsection