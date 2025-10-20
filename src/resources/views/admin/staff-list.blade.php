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
<div>staff-list.blade.php</div>
<div class="table-container">
    <table class="table">
        <tr>
            <th>名前</th>
            <th>メールアドレス</th>
            <th>月次勤怠</th>
        </tr>
        <tr>
            <td class="data">西伶奈</td>
            <td class="data">reina.n@gmail.com</td>
            <td class="data">詳細</td>
        </tr>
    </table>
</div>
    
@endsection