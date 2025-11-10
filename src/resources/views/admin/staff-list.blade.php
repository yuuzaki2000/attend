@extends('layouts.admin-app')

@section('css')
<link rel="stylesheet" href="{{asset('css/staff_list.css')}}">
@endsection

@section('title')
<div class="title-container">
❚ スタッフ一覧
</div>
@endsection

@section('content')
<div class="table-container">
    <table class="table">
        <tr>
            <th>名前</th>
            <th>メールアドレス</th>
            <th>月次勤怠</th>
        </tr>
        @foreach ($users as $user)
        <tr>
            <td class="data">{{$user->name}}</td>
            <td class="data">{{$user->email}}</td>
            <td class="data">
                <form action="/admin/attendance/staff/{{$user->id}}" method="get">
                @csrf
                    <button type="submit">詳細</button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>
</div>
    
@endsection