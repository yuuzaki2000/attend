@extends('layouts.admin-app')

@section('css')
<link rel="stylesheet" href="{{asset('css/application_list.css')}}">
@endsection

@section('title')
申請一覧    
@endsection

@section('content')
<div class="container">
    <div class="header">admin-application.blade.php</div>
    <div class="table-container">
        <table>
            <tr>
                <th>状態</th>
                <th>名前</th>
                <th>対象日時</th>
                <th>申請理由</th>
                <th>申請日時</th>
                <th>詳細</th>
            </tr>
            @foreach ($appliedWorktimes as $appliedWorktime)
            <tr>
                <td>承認判定なし</td>
                <td>{{$appliedWorktime->user->name}}</td>
                <td>{{$appliedWorktime->date}}</td>
                <td>{{$appliedWorktime->application->reason}}</td>
                <td>{{$appliedWorktime->application->created_at}}</td>
                <td>詳細なし</td>
            </tr>                
            @endforeach
        </table>
    </div>
</div>    
@endsection