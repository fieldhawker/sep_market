<head>
    <meta charset="utf-8">
    <title>@yield('title')</title>
    <!-- for responsive -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- bootstrap -->
    <link href={{secure_asset("bootstrap/css/bootstrap.min.css")}} rel="stylesheet" type="text/css" />
    <!-- font awesome -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/earlyaccess/notosansjapanese.css" rel="stylesheet" type="text/css" />
    <!-- ionicons -->
    <link href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css" />


    
    <!-- page style -->
    @yield('style')
    
    <!-- adminLTE style -->
    <link href={{secure_asset("dist/css/AdminLTE.min.css")}} rel="stylesheet" type="text/css" />
    {{--    <link href={{secure_asset("dist/css/skins/skin-blue.min.css")}} rel="stylesheet" type="text/css" />--}}
    <link href={{secure_asset("dist/css/skins/skin-purple.min.css")}} rel="stylesheet" type="text/css" />
    
    <!-- common -->
    <link href={{secure_asset("css/common.css?" . date('YmdHis'))}} rel="stylesheet" type="text/css" />
</head>