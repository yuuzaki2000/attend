@extends('layouts.admin-app')

@section('css')
<link rel="stylesheet" href="{{asset('css/application_list.css')}}">
@endsection

@section('title')
申請一覧    
@endsection

@section('content')
<div class="content">
    <div>admin-application-list.blade.php</div>
    <div class="tab-group">
        <div>
            <input type="hidden" name="page" value="applicated">
            <button type="submit">申請済</button>
        </div>
        <div action="/stamp_correction_request/list" method="get">
            <input type="hidden" name="page" value="approved">
            <button type="submit">承認済</button>
        </div>
    </div>
    <div class="table-container">
        <table>
            <tr>
                <th class="table-header">状態</th>
                <th class="table-header">名前</th>
                <th class="table-header">対象日時</th>
                <th class="table-header">申請理由</th>
                <th class="table-header">申請日時</th>
                <th class="table-header">詳細</th>
            </tr>
            @foreach ($appliedWorktimes as $appliedWorktime)
            <tr>
                <td class="table-detail">承認判定なし</td>
                <td class="table-detail">{{$appliedWorktime?$appliedWorktime->user->name:null}}</td>
                <td class="table-detail">{{$appliedWorktime?$appliedWorktime->date:null}}</td>
                <td class="table-detail">{{$appliedWorktime?$appliedWorktime->application->reason:null}}</td>
                <td class="table-detail">{{$appliedWorktime?$appliedWorktime->application->created_at:null}}</td>
                @if($appliedWorktime)
                <td class="table-detail">
                    <form action="/stamp_correction_request/approve/{{$appliedWorktime->id}}" method="get">
                    @csrf
                        <button type="submit">詳細</button>
                    </form>
                </td>  
                @else
                <td class="table-detail">
                    <div>
                        <button type="submit">詳細</button>
                    </div>
                </td>
                @endif
            </tr>                
            @endforeach
        </table>
    </div>
</div>    
@endsection