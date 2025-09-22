@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{asset('css/attendance_atd')}}">
@endsection

@section('content')
<div class="container">
    <div><p>attendance</p></div>
    <div class="status">出勤中</div>
    <div class="date-sectioin"><p class="date">{{$current_date}}</p></div>
    <div><p class="time">{{$current_time}}</p></div>
    <input type="text" name="breaktimeId" value="{{$breaktimeId}}">
    <button>退勤</button>
    <button>休憩入</button>
</div>
@endsection