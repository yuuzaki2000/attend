@extends('layouts.auth')

@section('css')
<link rel="stylesheet" href="{{asset('email_verification.css')}}">
@endsection

@section('content')
<div class="container">
    <div>
        <p>verify-email.blade.php</p>
        <p>登録していただいたメールアドレスに認証メールを送付しました。</p>
        <p>メールに認証を完了してください。</p>
    </div>
    <div><a href="https://mailtrap.io/inboxes/3822474/messages">認証はこちらから</a></div>
    <form action="/email/verification-notification" method="post">
    @csrf
        <button>認証メールを再送する</button>
    </form>
</div>
@endsection