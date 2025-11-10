@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{asset('css/application_list.css')}}">
@endsection

@section('title')
❚ 申請一覧    
@endsection

@section('content')
<div class="content">
    <div class="tab-group">
            <form action="/stamp_correction_request/list" method="get">
            @csrf
                <input type="hidden" name="page" value="pendingApproval">
                <button type="submit">承認待ち</button>
            </form>
            <form action="/stamp_correction_request/list" method="get">
            @csrf
                <input type="hidden" name="page" value="approved">
                <button type="submit">承認済み</button>
            </form>
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
            @php
                $approval = App\Models\Approval::whereHas('application.worktime', function($query) use ($appliedWorktime){
                    $query->where('id', $appliedWorktime->id);
                })->first();
            @endphp
            <tr>
                <td class="table-detail">{{$approval->is_approved?'承認済み':'承認待ち'}}</td>
                <td class="table-detail">{{$appliedWorktime->user->name}}</td>
                <td class="table-detail">{{$appliedWorktime->date}}</td>
                <td class="table-detail">{{$appliedWorktime->remarks}}</td>
                <td class="table-detail">{{$appliedWorktime->application->created_at}}</td>
                <td class="table-detail">
                    <form action="/attendance/detail/{{$appliedWorktime->id}}" method="get">
                    @csrf
                        <button type="submit">詳細</button>
                    </form>
                </td>
            </tr>                
            @endforeach
        </table>
    </div>
</div>    
@endsection