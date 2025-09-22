@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{asset('css/attendance_off.css')}}">
@endsection

@section('content')
<div class="container">
    <div><p>attendance_off</p></div>
    <div class="status-section"><p class="status">勤務外</p></div>
    <div class="date-section"><p class="date">{{$current_date}}</p></div>
    <div class="time-section"><p class="time">{{$current_time}}</p></div>
    <div class="button-section">
        <form class="button-section__inner" action="/work/start" method="post">
        @csrf
            <button>出勤</button>
        </form>
    </div>
</div>
@endsection