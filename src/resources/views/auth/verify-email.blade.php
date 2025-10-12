@extends('layouts.auth')

@section('css')
<link rel="stylesheet" href="{{asset('css/verify_email.css')}}">
@endsection

@section('content')
<div class="container">
    <div class="comment-group">
        <p class="comment-upper">登録していただいたメールアドレスに認証メールを送付しました。</p>
        <p class="comment-bottom">メールに認証を完了してください。</p>
    </div>
    <div><a href="{{env('MY_INBOX_URL')}}" class="verification">認証はこちらから</a></div>
    <form action="/email/verification-notification" method="post">
    @csrf
        <button class="resend">認証メールを再送する</button>
    </form>
</div>
@endsection