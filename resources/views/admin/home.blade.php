@extends('layouts.admin_layout')

@section('title')
    管理画面 TOP
@stop
        
@section('content')

<!-- コンテンツヘッダ -->
<section class="content-header">
    <h1>DashBoard</h1>
    <!-- パンくず -->
    <ol class="breadcrumb">
        <li><a href="">Home</a></li>
        <li>DashBoard</li>
    </ol>
</section>

<!-- メインコンテンツ -->
<section class="content">

    <!-- コンテンツ1 -->
    <div class="row">
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-aqua">
                <div class="inner">
                    <h3>150<sup style="font-size: 15px">品</sup></h3>

                    <p>販売数</p>
                </div>
                <div class="icon">
                    <i class="fa fa-shopping-cart"></i>
                </div>
                <a href="#" class="small-box-footer">
                    More info <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-green">
                <div class="inner">
                    <h3>{{$items_count}}<sup style="font-size: 15px">品</sup></h3>

                    <p>商品数</p>
                </div>
                <div class="icon">
                    <i class="fa fa-cubes"></i>
                </div>
                <a href="/admin/items/" class="small-box-footer">
                    More info <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-yellow">
                <div class="inner">
                    <h3>{{$user_count}}<sup style="font-size: 15px">人</sup></h3>

                    <p>会員数</p>
                </div>
                <div class="icon">
                    <i class="ion ion-person-add"></i>
                </div>
                <a href="/admin/users/" class="small-box-footer">
                    More info <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-red">
                <div class="inner">
                    <h3>65</h3>

                    <p>Unique Visitors</p>
                </div>
                <div class="icon">
                    <i class="ion ion-pie-graph"></i>
                </div>
                <a href="#" class="small-box-footer">
                    More info <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <!-- ./col -->
    </div>
    
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">ボックスタイトル</h3>
        </div>
        <div class="box-body">
            <p>ボックスボディー</p>
        </div>
    </div>

    <div class="row">

        <!-- col -->
        <div class="col-xs-6">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">ボックスタイトル左</h3>
                </div>
                <div class="box-body">
                    <p>ボックスボディー</p>
                </div>
            </div>
        </div>

        <!-- col -->
        <div class="col-xs-6">
            <div class="box box-danger">
                <div class="box-header">
                    <h3 class="box-title">ボックスタイトル右</h3>
                </div>
                <div class="box-body">
                    <p>ボックスボディー</p>
                </div>
            </div>
        </div>


    </div><!-- end row -->

</section>


@endsection