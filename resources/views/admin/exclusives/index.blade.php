@extends('layouts.admin_layout')

@section('title')
    排他制御 一覧
    @stop

    @section('content')

            <!-- コンテンツヘッダ -->
    <section class="content-header">
        <h1>排他制御一覧</h1>
        <!-- パンくず -->
        <ol class="breadcrumb">
            <li><a href="/admin/">Home</a></li>
            <li>排他制御一覧</li>
        </ol>
    </section>

    <!-- メインコンテンツ -->
    <section class="content">

        <!-- コンテンツ1 -->
        <div class="row">
            
            <div class="col-xs-12">

                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">排他制御を一覧表示します</h3>
                    </div>

                                <!-- /.box-header -->
                        <div class="box-body">
                            
                            @if (Session::has('message'))

                                    @if (Session::get('message') == 'delete')
                                    <div class="alert alert-info">削除が完了しました。</div>
                                    @endif

                            @endif

                            <table id="listTable" class="table table-bordered table-striped dataTable"
                                   role="grid" aria-describedby="listTable_info">
                                <thead>
                                <tr role="row">
                                    <th class="sorting_asc" tabindex="0" aria-controls="listTable" rowspan="1"
                                        colspan="1" aria-sort="ascending"
                                        aria-label="Rendering engine: activate to sort column descending"
                                        style="width: 20px;">ID
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="listTable" rowspan="1"
                                        colspan="1" aria-label="Browser: activate to sort column ascending"
                                        style="width: 202px;">画面名
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="listTable" rowspan="1"
                                        colspan="1" aria-label="Platform(s): activate to sort column ascending"
                                        style="width: 178px;">対象ID
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="listTable" rowspan="1"
                                        colspan="1"
                                        aria-label="Engine version: activate to sort column ascending"
                                        style="width: 138px;">管理者
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="listTable" rowspan="1"
                                        colspan="1"
                                        aria-label="Engine version: activate to sort column ascending"
                                        style="width: 138px;">有効期限
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="listTable" rowspan="1"
                                        colspan="1"
                                        aria-label="Engine version: activate to sort column ascending"
                                        style="width: 20px;">
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                {{--*/ $even = true /*--}}
                                @foreach($exclusives as $exclusive)
                                    @if ($even)
                                        <tr role="row" class="even">
                                    @else
                                        <tr role="row" class="odd">
                                            @endif
                                            <td>{{$exclusive->id}}</td>
                                            <td>{{\Config::get('screen.name.'.$exclusive->screen_number)}}</td>
                                            <td>{{$exclusive->target_id}}</td>
                                            <td>{{$exclusive->name}}</td>
                                            <td>{{$exclusive->expired_at}}</td>
                                            <td class="text-center">
                                                <form method="POST" action="/admin/exc/{{$exclusive->id}}">
                                                    <input type="hidden" name="_method" value="delete">
                                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                                    <input type="submit" value="削除"
                                                           class="btn btn-danger btn-sm btn-destroy"
                                                           onclick='return confirm("ID:{{$exclusive->id}}を削除してよろしいですか？");'>
                                                </form>
                                            </td>
                                        </tr>
                                        {{--*/ $even = !$even /*--}}
                                        @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th rowspan="1" colspan="1">ID</th>
                                    <th rowspan="1" colspan="1">画面名</th>
                                    <th rowspan="1" colspan="1">対象ID</th>
                                    <th rowspan="1" colspan="1">管理者</th>
                                    <th rowspan="1" colspan="1">有効期限</th>
                                    <th rowspan="1" colspan="1"></th>
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
            $("#listTable").DataTable({
                "aaSorting": [ [0,'desc'] ]
            });
        });
    </script>

@endsection
