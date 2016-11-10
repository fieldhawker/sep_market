@extends('layouts.admin_layout')

@section('title')
    会員詳細
    @stop

    @section('content')

            <!-- コンテンツヘッダ -->
    <section class="content-header">
        <h1>会員詳細</h1>
        <!-- パンくず -->
        <ol class="breadcrumb">
            <li><a href="/admin/">Home</a></li>
            <li><a href="/admin/users">会員一覧</a></li>
            <li>会員詳細</li>
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
                        <h3 class="box-title">会員の詳細を表示します</h3>
                    </div>
                    
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form role="form" method="GET" action="">
                        <div class="box-body">

                            <h4>UID</h4>
                            <div class="input-group">
                                {{ e($user->uid) }}
                            </div>

                            <h4>名前</h4>
                            <div class="input-group">
                                {{ e($user->name) }}
                            </div>

                            <h4>カナ</h4>
                            <div class="input-group">
                                {{ e($user->kana) }}
                            </div>

                            <h4>メールアドレス</h4>
                            <div class="input-group">
                                {{ e($user->email) }}
                            </div>
                            

                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">

                            <a href="/admin/users/">
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
