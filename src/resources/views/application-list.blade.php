@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{asset('css/application_list.css')}}">
@endsection

@section('title')
申請一覧    
@endsection

@section('content')
<div class="container">
    <div class="header">application-list.blade.php</div>
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
            <tr>
                <td>承認待ち</td>
                <td>西伶奈</td>
                <td>2023/06/01</td>
                <td>遅延のため</td>
                <td>2023/06/02</td>
                <td>詳細</td>
            </tr>
        </table>
    </div>
</div>    
@endsection