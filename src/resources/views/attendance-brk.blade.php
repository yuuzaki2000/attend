@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{asset('css/attendance_brk.css')}}">
@endsection

@section('content')
<div class="container">
    <div class="status-section"><p class="status">休憩中</p></div>
    <div class="date-section"><p class="date">{{$current_date}}</p></div>
    <div class="time-section"><p style="font-size: 30px; text-align:center">{{$current_time}}</p></div>
    <div class="button-section">
        <form class="button-section__inner" action="/break/out" method="post">
        @csrf
            <input type="hidden" name="breaktimeId" value={{$breaktimeId}}>
            <button>休憩戻</button>
        </form>
    </div>
</div>
@endsection