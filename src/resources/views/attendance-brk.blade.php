@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{asset('css/attendance_brk.css')}}">
@endsection

@section('content')
<div class="container">
    <div><p>attendance_brk</p></div>
    <div class="status-section"><p class="status">休憩中</p></div>
    <div class="date-section"><p class="date">{{$current_date}}</p></div>
    <div class="time-section"><p class="time">{{$current_time}}</p></div>
    <div class="button-section">
        <div class="button-section__inner">
            <button>休憩戻</button>
        </div>
    </div>
</div>
@endsection