<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>表单组合</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="stylesheet" href="{{asset('layuiadmin/layui/css/layui.css')}}" media="all">
    <link rel="stylesheet" href="{{asset('layuiadmin/style/admin.css')}}" media="all">
</head>
<body>
<div class="layui-form" lay-filter="form-article" id="form-article" style="padding: 20px 0 0 0;">
    <div class="layui-form-item">
        <label class="layui-form-label">文章标题</label>
        <div class="layui-input-inline">
            <input type="text" name="title" lay-verify="required" placeholder="请输入标题" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">描述</label>
        <div class="layui-input-inline">
            <input type="text" name="description" lay-verify="required" placeholder="请输入描述" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">关键字</label>
        <div class="layui-input-inline">
            <input type="text" name="keywords" lay-verify="required" placeholder="请输入关键字" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">文章分类</label>
        <div class="layui-input-block">
            <select name="category" lay-filter="category">
                <option value=""></option>
                <option value="0">写作</option>
                <option value="1" selected="">阅读</option>
                <option value="2">游戏</option>
                <option value="3">音乐</option>
                <option value="4">旅行</option>
            </select>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">缩略图</label>
        <div class="layui-input-block">
            <div class="layui-upload">
                <button type="button" class="layui-btn" id="test-upload-normal">上传图片</button>
                <div class="layui-upload-list">
                    <img class="layui-upload-img" id="test-upload-normal-img">
                    <p id="test-upload-demoText"></p>
                </div>
            </div>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">是否置顶</label>
        <div class="layui-input-inline">
            <input type="checkbox" name="is_top" lay-skin="switch" lay-text="ON|OFF">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">文章内容</label>
        <div class="layui-input-block">
            <div class="layui-card">
                <div class="layui-card-body">
                    <div class="layui-tab layui-tab-card">
                        <ul class="layui-tab-title">
                            <li class="layui-this">markdown</li>
                            <li>预览markdown效果</li>
                        </ul>
                        <div class="layui-tab-content">
                            <div class="layui-tab-item layui-show">
                                <textarea name="markdown" rows="8" required lay-verify="required" placeholder="请输入" class="layui-textarea">11</textarea>
                            </div>
                            <div class="layui-tab-item">
                                <textarea name="html" rows="8" required lay-verify="required" placeholder="请输入" class="layui-textarea">22</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="layui-form-item layui-hide">
        <input type="button" lay-submit lay-filter="LAY-article-front-submit" id="LAY-article-front-submit" value="确认">
        <input type="hidden" id="image_path" name="image_path" value="">
    </div>
</div>
<script src="{{asset('layuiadmin/layui/layui.js')}}"> </script>
<script>
    layui.config({
        base: "{{asset('layuiadmin')}}"+'/' //静态资源所在路径
    }).extend({
        index: 'lib/index' //主入口模块
    }).use(['index','form','upload'],function () {
        var $ = layui.$
            ,upload = layui.upload
            ,form = layui.form;

        /* 自定义验证规则 */
        form.verify({
            title: function(value){
                if(value.length < 5){
                    return '标题至少得5个字符啊';
                }
            },
        });

        //普通图片上传
        var uploadInst = upload.render({
            elem: '#test-upload-normal'
            ,url: '{{ url('admin/article/uploadImage')}}'
            ,field:'image_file'
            ,data: {'_token': '{{csrf_token()}}'}
            ,before: function(obj){
                //预读本地文件示例，不支持ie8
                obj.preview(function(index, file, result){
                    $('#test-upload-normal-img').attr('src', result); //图片链接（base64）
                    $('#test-upload-normal-img').css('width','180px')
                });
            }
            ,done: function(res){
                //如果上传失败
                if(res.code == 200){
                    $('#image_path').val(res.data.path);
                }
                return layer.msg(res.message);
            }
            ,error: function(){
                //演示失败状态，并实现重传
                var demoText = $('#test-upload-demoText');
                demoText.html('<span style="color: #FF5722;">上传失败</span><br /><button type="button" class="layui-btn demo-reload">重试</button>');
                demoText.find('.demo-reload').on('click', function(){
                    uploadInst.upload();
                });
            }
        });
    });
</script>
</body>
</html>
