<!doctype html>

@include('layouts.admin_header')

<body class="skin-purple">
<div class="wrapper">

    <!-- トップメニュー -->
    <header class="main-header">

        <!-- ロゴ -->
        <a href="" class="logo">管理画面</a>

        <!-- トップメニュー -->
        <nav class="navbar navbar-static-top" role="navigation">

            <!-- メニュー項目 -->
            <!-- 小さくなった時に消す -->
            {{--<div class="collapse navbar-collapse" id="navbar-collapse">--}}

                <!-- サイドバー制御 -->
                <a href="" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                </a>

                {{--<ul class="nav navbar-nav">--}}
                    {{--<li><a href="">顧客管理</a></li>--}}
                    {{--<li><a href="">売上管理</a></li>--}}
                    <!-- doropdown -->
                    {{--<li class="dropdown">--}}
                        {{--<a href="" class="dropdown-toggle" data-toggle="dropdown">その他<span class="caret"></span></a>--}}
                        {{--<ul class="dropdown-menu" role="menu">--}}
                            {{--<li><a href="">その他１</a></li>--}}
                            {{--<li><a href="">その他２</a></li>--}}
                        {{--</ul>--}}
                    {{--</li>--}}
                {{--</ul>--}}

                <!-- 右に寄せるメニュ :navbar-rightとかもあるが、マージが無い -->
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <li><a href="/admin/logout"><i class="fa fa-sign-out"></i>ログアウト</a></li>
                    </ul>
                </div>

            {{--</div>--}}
        </nav>
    </header><!-- end header -->

    <!-- サイドバー -->
    @include('layouts.admin_sidebar')

    <!-- content -->
    <div class="content-wrapper">

        @yield('content')
                

    </div>



    <!-- フッター -->
    @include('layouts.admin_footer')

</div><!-- end wrapper -->
<!-- JS -->

<!-- jquery -->
<script src={{secure_asset("plugins/jQuery/jquery-2.2.3.min.js")}} type="text/javascript"></script>
<!-- bootstrap -->
<script src={{secure_asset("bootstrap/js/bootstrap.min.js")}} type="text/javascript"></script>

<!-- page script -->
@yield('script')

<!-- adminLTE -->
<script src={{secure_asset("dist/js/app.min.js")}} type="text/javascript"></script>



</body>
</html>