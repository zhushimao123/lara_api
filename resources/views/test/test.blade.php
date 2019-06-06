<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>用户信息</title>
</head>
<body>
        <table>
            <tr>
                <th>运营者id</th>
                <th>企业名字</th>
                <th>运营者号码</th>
            </tr>
            <tr>
                <td> {{$result-> uid}}</td>
                <td> {{$result-> name}}</td>
                <td> {{$result-> tel}}</td>
            </tr>
        </table>
</body>
</html>