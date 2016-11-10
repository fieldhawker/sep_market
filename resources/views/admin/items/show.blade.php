@extends('layouts.admin_layout')

@section('title')
    商品詳細
    @stop

    @section('content')

            <!-- コンテンツヘッダ -->
    <section class="content-header">
        <h1>商品詳細</h1>
        <!-- パンくず -->
        <ol class="breadcrumb">
            <li><a href="/admin/">Home</a></li>
            <li><a href="/admin/items">商品一覧</a></li>
            <li>商品詳細</li>
        </ol>
    </section>

    <!-- メインコンテンツ -->
    <section class="content">

        <!-- コンテンツ1 -->
        <div class="row">

            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">商品の詳細を表示します</h3>
                    </div>
                    
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form role="form" method="GET" action="">
                        <div class="box-body">

                            <h4>会員名</h4>
                            <div class="input-group">
                                {{ e($item->uname) }}
                            </div>

                            <h4>名前</h4>
                            <div class="input-group">
                                {{ e($item->name) }}
                            </div>

                            <h4>価格</h4>
                            <div class="input-group">
                                {{ e($item->price) }}
                            </div>

                            <h4>説明</h4>
                            <div class="input-group">
                                {{ e($item->caption) }}
                            </div>

                            <h4>状態</h4>
                            <div class="input-group">
                                {{ e(Config::get('const.items.status.'.$item->status)) }}
                            </div>

                            <h4>商品の状態</h4>
                            <div class="input-group">
                                {{ e(Config::get('const.items.items_status.'.$item->items_status)) }}
                            </div>

                            <h4>販売開始日時</h4>
                            <div class="input-group">
                                {{ e($item->started_at) }}
                            </div>

                            <h4>販売終了日時</h4>
                            <div class="input-group">
                                {{ e($item->ended_at) }}
                            </div>

                            <h4>配送料</h4>
                            <div class="input-group">
                                {{ e(Config::get('const.items.delivery_charge.'.$item->delivery_charge)) }}
                            </div>

                            <h4>発送方法</h4>
                            <div class="input-group">
                                {{ e(Config::get('const.items.delivery_plan.'.$item->delivery_plan)) }}
                            </div>

                            <h4>発送元の都道府県</h4>
                            <div class="input-group">
                                {{ e(Config::get('const.pref.'.$item->pref)) }}
                            </div>

                            <h4>発送日数</h4>
                            <div class="input-group">
                                {{ e(Config::get('const.items.delivery_date.'.$item->delivery_date)) }}
                            </div>

                            <h4>コメント</h4>
                            <div class="input-group">
                                {{ e($item->comment) }}
                            </div>
                            

                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">

                            <a href="/admin/items/">
                                <button type="button" class="btn btn-default btn-lg">戻る</button>
                            </a>
                        </div>

                    </form>
                </div>
                <!-- /.box -->

            </div>

        </div><!-- end row -->

    </section>


@endsection


@section('style')

@endsection

@section('script')

    <script>

    </script>

@endsection
