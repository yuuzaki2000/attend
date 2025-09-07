@extends('layouts.auth')

@section('css')
<link rel="stylesheet" href="{{asset('css/login.css')}}">
@endsection

@section('title')
ログイン
@endsection

@section('content')
<form action="{{route('login')}}" method="post">
@csrf
    <div class="email-container">
        <div><p class="email-text">メールアドレス</p></div>
        <input type="text" class="email-input" name="email">
    </div>
    @error('email')
    <div>
        <p>{{$errors->first('email')}}</p>
    </div>
    @enderror
    <div class="password-container">
        <div><p class="password-text">パスワード</p></div>
        <input type="password" class="password-input" name="password">
    </div>
    @error('password')
    <div>
        <p>{{$errors->first('password')}}</p>
    </div>
    @enderror
    <button class="register-button">ログインする</button>
    <div class="login-transition"><a href="/register" class="login-transition-text">新規登録はこちら</a></div>
</form>
@endsection