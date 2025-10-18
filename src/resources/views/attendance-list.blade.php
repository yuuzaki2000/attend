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
                }else{
                    $startTime = null;
                }
                //配列の書き方で、breaktimeのデータを検索する
                //worktimeのidはEloquentでとってくる
                @endphp
                <td class="attendance-data">{{$day->format('Y/m/d')}}</td>
                <td class="attendance-data">{{$startTime}}</td>
                <td class="attendance-data">17:00</td>
                <td class="attendance-data">1:00</td>
                <td class="attendance-data">1:00</td>
                <td class="attendance-data">
                    <form action="" method="get">
                    @csrf
                        <button type="submit">詳細</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </table>
    </div>
@endsection