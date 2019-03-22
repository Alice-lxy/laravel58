<form action="/admin/weixin/usersign" method="post">
    {{csrf_field()}}
    <textarea name="sign" id="" placeholder="30字符以内" cols="100" rows="10"></textarea>
    <input type="submit" value="创建">
</form>