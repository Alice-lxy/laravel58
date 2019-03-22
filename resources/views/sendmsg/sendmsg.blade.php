<form action="/admin/weixin/sendmsg" method="post">
    {{csrf_field()}}
    <textarea name="text" id="" cols="100" rows="10"></textarea>
    <input type="submit" value="发送">
</form>
<script src=""></script>