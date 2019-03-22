<html>
<head>
    <title>larvel - @yield('title')</title>{{--应用程序名称 - @yield('title)--}}
</head>
<body>
    {{--top--}}
    @section('header')
        <p style="color: #a6e1ec">This is the parent header...</p>
    @show

    {{--center--}}
    <div class="container">
        @yield('content')
    </div>

    {{--footer--}}
    @section('footer')
        <p style="color: #a6e1ec">This is the parent footer...</p>
    @show
</body>
</html>