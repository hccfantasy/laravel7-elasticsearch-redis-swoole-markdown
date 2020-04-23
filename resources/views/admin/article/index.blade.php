<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>layuiAdmin 内容系统 - 文章列表</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="stylesheet" href="{{asset('layuiadmin/layui/css/layui.css')}}" media="all">
    <link rel="stylesheet" href="{{asset('layuiadmin/style/admin.css')}}" media="all">
</head>
<body>
<div class="layui-fluid">
    <div class="layui-card">
        <div class="layui-form layui-card-header layuiadmin-card-header-auto">
            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label">作者</label>
                    <div class="layui-input-inline">
                        <input type="text" name="author" placeholder="请输入" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">标题</label>
                    <div class="layui-input-inline">
                        <input type="text" name="title" placeholder="请输入" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">文章标签</label>
                    <div class="layui-input-inline">
                        <select name="label">
                            <option value="">请选择标签</option>
                            <option value="0">美食</option>
                            <option value="1">新闻</option>
                            <option value="2">八卦</option>
                            <option value="3">体育</option>
                            <option value="4">音乐</option>
                        </select>
                    </div>
                </div>
                <div class="layui-inline">
                    <button class="layui-btn layuiadmin-btn-list" lay-submit lay-filter="LAY-app-contlist-search">
                        <i class="layui-icon layui-icon-search layuiadmin-button-btn"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="layui-card-body">
            <div style="padding-bottom: 10px;">
                <button class="layui-btn layuiadmin-btn-list" data-type="batchdel">删除</button>
                <button class="layui-btn layuiadmin-btn-list" data-type="add">添加</button>
            </div>
            <table id="LAY-app-content-list" lay-filter="LAY-app-content-list"></table>
            <script type="text/html" id="table-content-list">
                <a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="edit"><i class="layui-icon layui-icon-edit"></i>编辑</a>
                <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del"><i class="layui-icon layui-icon-delete"></i>删除</a>
            </script>
        </div>
    </div>
</div>

<script src="{{asset('layuiadmin/layui/layui.js')}}"> </script>
<script>
    layui.config({
        base: "{{asset('layuiadmin')}}"+'/' //静态资源所在路径
    }).extend({
        index: 'lib/index' //主入口模块
    }).use(['index', 'contlist', 'table'], function(){
        var $ = layui.$
            ,table = layui.table
            ,form = layui.form;

        table.render({
            elem: '#LAY-app-content-list'
            ,url:"{{url('admin/article/getListDatas')}}"
            ,cellMinWidth: 80 //全局定义常规单元格的最小宽度，layui 2.2.1 新增
            ,page: true //开启分页
            ,cols: [[
                {type: 'checkbox', fixed: 'left'}
                ,{field:'title',title: '文章标题'}
                ,{field:'keywords',title: '关键字', sort: true}
                ,{field:'description',title: '描述'}
                ,{field:'category_id', title: '分类'} //minWidth：局部定义当前单元格的最小宽度，layui 2.2.1 新增
                ,{field:'cover', title: '缩略图',templet:function(d){
                    var imgPath = "{{asset('')}}";
                    return "<img src='"+imgPath+d.cover+"'>"
                    }}
                ,{field:'is_top', title: '是否置顶', sort: true}
                ,{field:'markdown', title: '文章内容markdown'}
                ,{field:'html',title: '文章html'}
                ,{fixed: '', width: 165, title: '操作', align:'center', toolbar: '#table-content-list'}
            ]]
        });

        //监听搜索
        form.on('submit(LAY-app-contlist-search)', function(data){
            var field = data.field;
            //执行重载
            table.reload('LAY-app-content-list', {
                where: field
            });
        });

        //操作功能
        var  active = {
            batchdel: function(){
                var checkStatus = table.checkStatus('LAY-app-content-list')
                    ,checkData = checkStatus.data; //得到选中的数据
                if(checkData.length === 0){
                    return layer.msg('请选择数据');
                }
                console.log(checkData);
                layer.confirm('确定删除吗？', function(index) {
                    $.ajax({
                        url:"{{url('admin/article/destroy')}}",
                        method:'get',
                        dataType:'json',
                        data:field,
                        success:function (res) {
                            layer.msg(res.message);
                        }
                    });
                    table.reload('LAY-app-content-list');
                });
            },
            add: function(){
                layer.open({
                    type: 2
                    ,title: '添加文章'
                    ,content: '{{url("admin/article/create")}}'
                    ,area: ['550px', '550px']
                    ,btn: ['确定', '取消']
                    ,yes: function(index, layero){
                        var iframeWindow = window['layui-layer-iframe'+ index]
                            ,submitID = 'LAY-article-front-submit'
                            ,submit = layero.find('iframe').contents().find('#'+ submitID);
                        //监听提交
                        iframeWindow.layui.form.on('submit('+ submitID +')', function(data){
                            var field = data.field; //获取提交的字段
                            field['_token'] = "{{csrf_token()}}";
                            //提交 Ajax 成功后，静态更新表格中的数据
                            $.ajax({
                                url:"{{url('admin/article/store')}}",
                                method:'POST',
                                dataType:'json',
                                data:field,
                                success:function (res) {
                                    layer.msg(res.message);
                                }
                            });
                            table.reload('LAY-article-front-submit'); //数据刷新
                            layer.close(index); //关闭弹层
                        });
                        submit.trigger('click');
                    }
                });
            }
        };

        $('.layui-btn.layuiadmin-btn-list').on('click', function(){
            var type = $(this).data('type');
            active[type] ? active[type].call(this) : '';
        });

    });
</script>
</body>
</html>
