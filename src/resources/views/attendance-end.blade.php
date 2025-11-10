@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{asset('css/attendance_end.css')}}">
@endsection

@section('content')
<div class="container">
    <div class="status-section"><p class="status">退勤済</p></div>
    <div class="date-section"><p class="date">{{$current_date}}</p></div>
    <div class="time-section"><p style="font-size: 30px; text-align:center">{{$current_time}}</p></div>
    <div class="button-section">
        <div class="button-section__inner">
            <button>お疲れ様でした</button>
        </div>
    </div>
</div>
@endsection