@extends('layouts.admin_layout')

@section('title')
    操作ログ画面 一覧
    @stop

    @section('content')

    <!-- コンテンツヘッダ -->
    <section class="content-header">
        <h1>操作ログ一覧</h1>
        <!-- パンくず -->
        <ol class="breadcrumb">
            <li><a href="/admin/">Home</a></li>
            <li>操作ログ一覧</li>
        </ol>
    </section>

    <!-- メインコンテンツ -->
    <section class="content">

        <!-- コンテンツ1 -->
        <div class="row">
            
            <div class="col-xs-12">

                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">操作ログを一覧表示します</h3>
                    </div>

                        <!-- /.box-header -->
                        <div class="box-body">
                            
                            <table id="listTable" class="table table-bordered table-striped dataTable"
                                   role="grid" aria-describedby="listTable_info">
                                <thead>
                                <tr role="row">
                                    <th class="sorting_desc" tabindex="0" aria-controls="listTable" rowspan="1"
                                        colspan="1" aria-sort="descending"
                                        aria-label="Rendering engine: activate to sort column ascending"
                                        style="width: 10px;">ID
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="listTable" rowspan="1"
                                        colspan="1" aria-label="Browser: activate to sort column ascending"
                                        style="width: 160px;">操作内容
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="listTable" rowspan="1"
                                        colspan="1" aria-label="Platform(s): activate to sort column ascending"
                                        style="width: 30px;">対象ID
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="listTable" rowspan="1"
                                        colspan="1"
                                        aria-label="Engine version: activate to sort column ascending"
                                        style="width: 70px;">実行時間
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="listTable" rowspan="1"
                                        colspan="1"
                                        aria-label="Engine version: activate to sort column ascending"
                                        style="width: 170px;">コメント
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="listTable" rowspan="1"
                                        colspan="1"
                                        aria-label="Engine version: activate to sort column ascending"
                                        style="width: 60px;">作業者
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                {{--*/ $even = true /*--}}
                                @foreach($operation_logs as $operation_log)
                                    @if ($even)
                                        <tr role="row" class="even">
                                    @else
                                        <tr role="row" class="odd">
                                    @endif
                                            <td>{{$operation_log->id}}</td>
                                            <td>{{\Config::get('screen.message.'.$operation_log->screen_number)}}</td>
                                            <td>{{$operation_log->target_id}}</td>
                                            <td>{{$operation_log->executed_at}}</td>
                                            <td style="word-break: break-all;">{{$operation_log->comment}}</td>
                                            <td>{{$operation_log->name}}</td>
                                        </tr>
                                        {{--*/ $even = !$even /*--}}
                                        @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th rowspan="1" colspan="1">ID</th>
                                    <th rowspan="1" colspan="1">操作内容</th>
                                    <th rowspan="1" colspan="1">対象ID</th>
                                    <th rowspan="1" colspan="1">実行時間</th>
                                    <th rowspan="1" colspan="1">コメント</th>
                                    <th rowspan="1" colspan="1">作業者</th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                        <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            
        </div><!-- end row -->

    </section>


    @endsection


    @section('style')

    <!-- DataTables -->
    <link rel="stylesheet" href={{asset("plugins/datatables/dataTables.bootstrap.css")}}>

    @endsection

    @section('script')

            <!-- DataTables -->
    <script src={{asset("plugins/datatables/jquery.dataTables.min.js")}}></script>
    <script src={{asset("plugins/datatables/dataTables.bootstrap.min.js")}}></script>
    <!-- SlimScroll -->
    <script src={{asset("plugins/slimScroll/jquery.slimscroll.min.js")}}></script>
    <!-- FastClick -->
    <script src={{asset("plugins/fastclick/fastclick.js")}}></script>

    <script>
        $(function () {
            $('#listTable').DataTable({
                    "aaSorting": [ [0,'desc'] ],
//                    "bAutoWidth": false,
//                    "aoColumns": [ null,null,null,null,
//                        {"sWidth" : "80px"},
//                        null,
//                    ]
            });
        });
    </script>

@endsection
