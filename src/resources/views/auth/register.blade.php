@extends('layouts.auth')

@section('css')
<link rel="stylesheet" href="{{asset('css/register.css')}}">
@endsection

@section('title')
会員登録
@endsection

@section('content')
<form action="{{route('register')}}" method="post">
@csrf
    <div class="name-container">
        <div class="name-text"><p>名前</p></div>
        <input type="text" class="name-input" name="name" value="{{old('name')}}">
    </div>
    @error('name')
    <div><p>{{$errors->first('name')}}</p></div>
    @enderror
    <div class="email-container">
        <div><p class="email-text">メールアドレス</p></div>
        <input type="email" class="email-input" name="email" value="{{old('email')}}">
    </div>
    @error('email')
    <div><p>{{$errors->first('email')}}</p></div>
    @enderror
    <div class="password-container">
        <div><p class="password-text">パスワード</p></div>
        <input type="password" class="password-input" name="password">
    </div>
    @error('password')
    <div><p>{{$errors->first('password')}}</p></div>
    @enderror
    <div class="password-confirmation-container">
        <div><p class="password-confirmation-text">パスワード確認</p></div>
        <input type="password" class="password-confirmation-input" name="password_confirmation">
    </div>
    <button class="register-button">登録する</button>
    <div class="login-transition"><a href="/login" class="login-transition-text">ログインはこちら</a></div>
</form>
@endsection