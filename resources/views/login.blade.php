@extends('aps')

@section('title', '签到')
<script src="/js/jquery-3.2.1.min.js"></script>
@section('sidebar')
    {{--@parent--}}

@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-6 col-md-4"></div>
            <div class="col-xs-6 col-md-4">
                <button type="button" class="btn btn-danger" uid="{{$uid}}">签到</button>
                活跃度：
                <b id="p"></b>
            </div>

            <div class="col-xs-6 col-md-4"></div>
        </div>
    </div>
@endsection
<script>
    $(function(){
       $('.btn').click(function () {
            var _this = $(this);
            var uid = _this.attr('uid');
            $.ajax({
                url:'sig',
                type:'get',
                data:{uid:uid},
                dataType:'json',
                success:function (res) {
                    $('#p').text(res.num);
                }
            });
       })
    })

</script>