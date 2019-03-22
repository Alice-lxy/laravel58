<table>
    <tr>
        <td>id</td>
        <td style="padding: 8px">标签名</td>
        <td>粉丝数</td>
    </tr>
@foreach($data as $v)
        <tr>
            <td>{{$v['id']}}</td>
            <td style="padding: 8px">{{$v['name']}}</td>
            <td>{{$v['count']}}</td>
        </tr>
@endforeach
</table>