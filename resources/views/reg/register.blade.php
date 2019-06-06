<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>企业注册</title>
</head>
<body>
        <form action="regdo" method="post" enctype="multipart/form-data">
            企业名称:
            <input type="text" name="name"><br>
            营业执照
            <input type="file" name="myfile"><br>
            运营者身份证：
            <input type="password" name="pwd"><br>
            运营者手机：
            <input type="text" name="tel"><br>
            企业注册号:
            <input type="text" name="regs"><br>
            <input type="submit" value="注册">
        </form>
</body>
</html>