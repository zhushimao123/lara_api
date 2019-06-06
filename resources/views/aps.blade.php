<html>
<head>
    <title> @yield('title')</title>
    <script src="/js/jquery-3.2.1.min.js"></script>
    <script src="/bootstrap/js/bootstrap.js"></script>
    <link rel="stylesheet" href="/bootstrap/css/bootstrap.css">
</head>
<body>
@section('sidebar')
    这里是侧边栏
@show

<div class="container">
    @yield('content')
</div>
</body>
</html>