@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{asset('css/attendance_off.css')}}">
@endsection

@section('content')
<div class="container">
    <div><p>attendance_off</p></div>
    <div class="status-section"><p class="status">勤務外</p></div>
    <div class="date-section"><p class="date">２０２３年６月１日（木）</p></div>
    <div class="time-section"><p class="time">{{$current_time}}</p></div>
    <div class="button-section">
        <div class="button-section__inner">
            <button>出勤</button>
        </div>
    </div>
</div>
@endsection