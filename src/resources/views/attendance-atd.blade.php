@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{asset('css/attendance_atd.css')}}">
@endsection

@section('content')
<div class="container">
    <div class="status-section"><p class="status">出勤中</p></div>
    <div class="date"><p>{{$current_date}}</p></div>
    <div class="time-section"><p style="font-size: 30px; text-align:center">{{$current_time}}</p></div>
    <div class="button-section">
        <div class="button-section__inner">
            <form action="/work/end" method="post">
            @csrf
                <input type="hidden" name="worktimeId" value="{{$worktimeId}}">
                <button class="end-button" style="background-color: #000; color:#FFF">退勤</button>
            </form>
            <form action="/break/in" method="post">
            @csrf
                <input type="hidden" name="worktimeId" value="{{$worktimeId}}">
                <button class="start-break-button" style="background-color: #FFF; color:#000">休憩入</button>
            </form>
        </div>
    </div>
</div>
@endsection