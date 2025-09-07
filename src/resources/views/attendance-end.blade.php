@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{asset('css/attendance_first')}}">
@endsection

@section('content')
<div class="container">
    <div><p>attendance_end</p></div>
    <div class="status">退勤済</div>
    <div><p class="date">２０２３年６月１日（木）</p></div>
    <div><p class="time">08:00</p></div>
    <div><p>お疲れ様でした。</p></div>
</div>
@endsection