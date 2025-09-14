@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{asset('css/attendance_atd')}}">
@endsection

@section('content')
<div class="container">
    <div><p>attendance</p></div>
    <div class="status">出勤中</div>
    <div class="date-sectioin"><p class="date">２０２３年６月１日（木）</p></div>
    <div><p class="time">08:00</p></div>
    <button>退勤</button>
    <button>休憩入</button>
</div>
@endsection