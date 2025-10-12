<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <div>
        <div><p>application.blade.php</p></div>
        <div>
            <table>
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
                @foreach ($worktimes as $worktime)
                <tr>
                    <td></td>
                    <td>{{$worktime->user->name}}</td>
                    <td>{{$worktime->date}}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
    

</body>
</html>