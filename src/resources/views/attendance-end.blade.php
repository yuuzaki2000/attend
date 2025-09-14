@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{asset('css/attendance_end.css')}}">
@endsection

@section('content')
<div class="container">
    <div><p>attendance_end</p></div>
    <div class="status-section"><p class="status">退勤済</p></div>
    <div class="date-section"><p class="date">２０２３年６月１日（木）</p></div>
    <div class="time-section"><p class="time">{{$current_time}}</p></div>
    <div class="button-section">
        <div class="button-section__inner">
            <button>お疲れ様でした</button>
        </div>
    </div>
</div>
@endsection