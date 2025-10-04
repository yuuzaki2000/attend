@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{asset('css/attendance_detail.css')}}">
@endsection
@section('title')
❚　勤怠詳細
@endsection
@section('content')
    <div>
        <div class="table-container">
            <table>
                <tr class="table-row">
                    <th class="table-header">名前</th>
                    <td class="table-data">西 伶奈</td>
                </tr>
                <tr class="table-row">
                    <th class="table-header">日付</th>
                    <td class="table-data">2023年</td>
                    <td class="table-data"></td>
                    <td class="table-data">6月1日</td>
                </tr>
                <tr class="table-row">
                    <th class="table-header">出勤・退勤</th>
                    <td class="table-data">09：00</td>
                    <td class="table-data">～</td>
                    <td class="table-data">18：00</td>
                </tr>
                <tr class="table-row">
                    <th class="table-header">休憩</th>
                    <td class="table-data">12：00</td>
                    <td class="table-data">～</td>
                    <td class="table-data">13：00</td>
                </tr>
                <tr class="table-row">
                    <th class="table-header">備考</th>
                    <td class="table-data" colspan="3"></td>
                </tr>
            </table>
            <div class="update-button-container">
                <button>修正</button>
            </div>
        </div>
    </div>
@endsection