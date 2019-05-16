<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>登陆</title>
    <script src="js/jquery-3.2.1.min.js"></script>
</head>
<body>
      <h3>注册，登陆</h3>
      <form action="/userinfo"  method="post">
          <input type="text" name="name" id="name"><br>
          <input type="password" name="pass" id="pass"><br>
          <input type="text" name="email" id="email"> <br>
          <input type="button"  id="btn" value="登陆">
      </form>
    ________________________________________________________<br>




      <script src="http://client.1809a.com/user.php?uid=12"></script>
</body>
</html>
<script>
        $(function(){
            function userinfo(d)
        {
            $('#btn').click(function(){
            var name = userinfo(d.name);
            console.log(name);
            })
        }
        })
       


    </script>

    