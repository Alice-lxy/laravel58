@extends('layout.bst')
@section('title')
@endsection
@section('header')
@endsection
@section('content')
    <h1 align="center">USER LOGIN</h1>
    <form class="form-horizontal" action="http://passport.shop.com/login" method="post" style="margin-left: 280px;width:500px">
        {{csrf_field()}}
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">Name</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" required name="name"  id="name" placeholder="Name">
            </div>
        </div>
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">Password</label>
            <div class="col-sm-10">
                <input type="password" class="form-control" required name="password" id="pwd" placeholder="***">
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="button" id="btn" class="btn btn-default">Sign in</button>
            </div>
        </div>
    </form>
<script>
    $(function(){
        $('#btn').click(function(){
            var name=$('#name').val()
            var pwd=$('#pwd').val()
            if(name==''){
                return false
            }
            if(pwd==''){
                return false
            }
            $.post(
                    "http://passport.shop.com/login",
                    {name:name,pwd:pwd},
                    function(msg){
                        if(msg.code==0){
                            alert('登陆成功')
                            history.go(-1)
                        }else{
                            console.log(msg)
                        }
                    },'json'
            )
        })
    })
</script>
@endsection
@section('footer')
@endsection