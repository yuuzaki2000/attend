<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://unpkg.com/ress/dist/ress.min.css">
    <link rel="stylesheet" href="{{asset('css/admin_app.css')}}">
    @yield('css')
</head>
<body>
    <div class="header">
        <div class="header-title">
            <img src="{{asset('img/logo.svg')}}" alt="ロゴ">
        </div>
        <nav class="header-nav">
            <ul class="header-nav-list">
                <li class="header-nav-item">
                    <form action="/admin/attendance/list" method="get">
                    @csrf
                        <button class="attendance-button">勤怠一覧</button>
                    </form>
                </li>
                <li class="header-nav-item">スタッフ一覧</li>
                <li class="header-nav-item">申請一覧</li>
                <li class="header-nav-item">
                    <form action="/admin/logout" method="post">
                    @csrf
                        <input type="hidden" name="guard" value="admin">
                        <button class="logout-button">ログアウト</button>
                    </form>
                </li>
            </ul>
        </nav>
    </div>
    <div class="main">
        <h2 class="inner-title">@yield('title')</h2>
        <div class="content">@yield('content')</div>
    </div>
</body>
</html>