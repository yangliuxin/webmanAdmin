@extends('layout.default')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                用户管理
                <small>数据列表</small>
            </h1>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box grid-box">

                        <div class="box-header with-border">
                            <div class="pull-right">

                                <div class="btn-group pull-right grid-create-btn" style="margin-right: 10px">
                                    <a href="/admin/users/create" class="btn btn-sm btn-success" title="新增">
                                        <i class="fa fa-plus"></i><span class="hidden-xs">&nbsp;&nbsp;新增</span>
                                    </a>
                                </div>

                            </div>
                            <div class="pull-left">
                                <div class="btn-group" style="margin-right: 5px" data-toggle="buttons">
                                    <label class="btn btn-sm btn-dropbox filter-btn" title="筛选">
                                        <i class="fa fa-filter"></i><span class="hidden-xs">&nbsp;&nbsp;筛选</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="box-header with-border filter-box hide" id="filter-box">
                            <form action="/admin/users" class="form-horizontal" method="get">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="box-body">
                                            <div class="fields-group">
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label"> 序号</label>
                                                    <div class="col-sm-8">
                                                        <div class="input-group input-group-sm">
                                                            <div class="input-group-addon">
                                                                <i class="fa fa-pencil"></i>
                                                            </div>
                                                            <input type="text" class="form-control id" placeholder="ID"
                                                                   name="id" value="">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="box-footer">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-2"></div>
                                            <div class="col-md-8">
                                                <div class="btn-group pull-left">
                                                    <button class="btn btn-info submit btn-sm"><i class="fa fa-search"></i>&nbsp;&nbsp;搜索
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>

                        <div class="box-body table-responsive no-padding">
                            <table class="table table-hover grid-table" id="grid-table64634d9f95d1e">
                                <thead>
                                <tr>
                                    <th>序号</th>
                                    <th>用户名称</th>
                                    <th>创建时间</th>
                                    <th>更新时间</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($usersList) == 0)
                                    <tr>
                                        <td colspan="5" class="empty-grid" style="padding: 100px;text-align: center;color: #999999">
                                            <svg t="1562312016538" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="2076" width="128" height="128" style="fill: #e9e9e9;">
                                                <path d="M512.8 198.5c12.2 0 22-9.8 22-22v-90c0-12.2-9.8-22-22-22s-22 9.8-22 22v90c0 12.2 9.9 22 22 22zM307 247.8c8.6 8.6 22.5 8.6 31.1 0 8.6-8.6 8.6-22.5 0-31.1L274.5 153c-8.6-8.6-22.5-8.6-31.1 0-8.6 8.6-8.6 22.5 0 31.1l63.6 63.7zM683.9 247.8c8.6 8.6 22.5 8.6 31.1 0l63.6-63.6c8.6-8.6 8.6-22.5 0-31.1-8.6-8.6-22.5-8.6-31.1 0l-63.6 63.6c-8.6 8.6-8.6 22.5 0 31.1zM927 679.9l-53.9-234.2c-2.8-9.9-4.9-20-6.9-30.1-3.7-18.2-19.9-31.9-39.2-31.9H197c-19.9 0-36.4 14.5-39.5 33.5-1 6.3-2.2 12.5-3.9 18.7L97 679.9v239.6c0 22.1 17.9 40 40 40h750c22.1 0 40-17.9 40-40V679.9z m-315-40c0 55.2-44.8 100-100 100s-100-44.8-100-100H149.6l42.5-193.3c2.4-8.5 3.8-16.7 4.8-22.9h630c2.2 11 4.5 21.8 7.6 32.7l39.8 183.5H612z" p-id="2077"></path>
                                            </svg>
                                        </td>
                                    </tr>
                                @endif
                                @foreach($usersList as $data)
                                    <tr data-key="{{$data['id']}}">

                                        <td>{{$data['id']}}</td>
                                        <td>{{$data['username']}}</td>
                                        <td>{{$data['created_at']}}</td>
                                        <td>{{$data['updated_at']}}</td>
                                        <td>
                                            <a href="/admin/users/edit/{{$data['id']}}"><i class="fa fa-edit"></i></a>
                                            <a href="javascript:void(0);" data-id="{{$data['id']}}" class="tree_branch_delete"><i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>


                        <div class="box-footer clearfix">
                            共 <b>{{$totalPages}}</b> 页，每页 {{$pageNums}} 条， 共 <b>{{$total}}</b> 条
                            <ul class="pagination pagination-sm no-margin pull-right">
                                <li class="page-item @if($pageNo == 1) disabled @endif" >@if($pageNo > 1)<a class="page-link" href="/admin/users?page={{$pageNo-1}}" rel="prev">&laquo;</a>@else <span class="page-link">&laquo;</span> @endif</li>
                                @for($i = 1; $i <= $totalPages; $i++)
                                    <li class="page-item @if($pageNo == $i) active @endif">@if($i != $pageNo)<a class="page-link" href="/admin/users?page={{$i}}">{{$i}}</a>@else<span class="page-link">{{$i}}</span>@endif</li>
                                @endfor
                                <li class="page-item @if($pageNo ==$totalPages) disabled @endif">@if($pageNo != $totalPages)<a class="page-link" href="/admin/users?page={{$totalPages}}"  rel="next">»</a>@else<span class="page-link">»</span>@endif</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>
    <script src="/app/webmanAdmin/assets/AdminLTE/plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <script src="/app/webmanAdmin/assets/AdminLTE-2.4.18/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="/app/webmanAdmin/assets/AdminLTE-2.4.18/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
    <script src="/app/webmanAdmin/assets/AdminLTE-2.4.18/bower_components/fastclick/lib/fastclick.js"></script>
    <script src="/app/webmanAdmin/assets/AdminLTE-2.4.18/dist/js/adminlte.min.js"></script>
    <script src="/app/webmanAdmin/assets/js/app.js"></script>
    <script src="/app/webmanAdmin/assets/nestable/jquery.nestable.js"></script>
    <script src="/app/webmanAdmin/assets/AdminLTE/plugins/select2/select2.full.min.js"></script>
    <script src="/app/webmanAdmin/assets/fontawesome-iconpicker/dist/js/fontawesome-iconpicker.min.js"></script>
    <script src="/app/webmanAdmin/assets/number-input/bootstrap-number-input.js"></script>
    <script src="/app/webmanAdmin/assets/jstree/jstree.js"></script>
    <script src="/app/webmanAdmin/assets/bootstrap-fileinput/js/plugins/canvas-to-blob.min.js"></script>
    <script src="/app/webmanAdmin/assets/bootstrap-fileinput/js/fileinput.min.js"></script>
    <script src="/app/webmanAdmin/assets/nestable/jquery.nestable.js"></script>
    <script src="/app/webmanAdmin/assets/sweetalert2/dist/sweetalert2.min.js"></script>
    <script src="/app/webmanAdmin/assets/toastr/build/toastr.min.js"></script>
    <script>
        $(function () {
            var $btn = $('.filter-btn');
            var $filter = $('#filter-box');

            $btn.unbind('click').click(function (e) {
                if ($filter.is(':visible')) {
                    $filter.addClass('hide');
                    $btn.removeClass('active')
                } else {
                    $filter.removeClass('hide');
                    $btn.addClass('active')
                }
            });

            $('.tree_branch_delete').click(function () {
                var id = $(this).data('id');
                swal({
                    title: "确认删除?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "确认",
                    showLoaderOnConfirm: true,
                    cancelButtonText: "取消",
                    preConfirm: function () {
                        return new Promise(function (resolve) {
                            $.ajax({
                                type: 'delete',
                                url: '/admin/users/delete/' + id,
                                data: {
                                    _method: 'delete'
                                },
                                async: true,
                                processData: true,
                                success: function (data) {
                                    toastr.success('删除成功 !');
                                    resolve(data);
                                }
                            });
                        });
                    }
                }).then(function (result) {
                    var data = result.value;
                    if (typeof data === 'object') {
                        if (data.code) {
                            swal(data.message, '', 'success');
                            location.reload()
                        } else {
                            swal(data.message, '', 'error');
                        }
                    }
                });
            });

        });
    </script>
@endsection