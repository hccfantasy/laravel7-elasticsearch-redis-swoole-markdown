<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Article;
use test\Mockery\Fixtures\EmptyTestCaseV5;

class ArticleController extends Controller
{
    /**
     * 列表页面展示
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.article.index');
    }

    /**
     * 获取所有列表数据
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getListDatas(Request $request)
    {
        $limit = $request->get('limit');
        //获取除软删除以外数据
        $list = Article::orderBy('created_at','desc')->paginate($limit)->toArray();
        $listDatas = [
            'code'=> '0',
            'msg' => '',
            'count'=>0,
            'data' =>[]
        ];
        if(!empty($list['data'])){
            $listDatas = [
                'code'=> '0',
                'msg' => '',
                'count'=>$list['total'],
                'data' =>$list['data']
            ];
        }
        return $listDatas;
    }

    /**
     * 添加页面展示
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.article.create');
    }

    /**
     * 数据添加
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,Article $articleModel)
    {
        $author = 'admin';
        //进行数据表单验证
        $this->validate($request,[
           'title' => 'required|min:5',//文章标题不能为空，且字段长度不能小于5
        ]);
        $data = $request->except('_token');
        $articleModel->title = $data['title'];
        $articleModel->description = $data['description'];
        $articleModel->keywords = $data['keywords'];
        $articleModel->category_id = $data['category'];
        $articleModel->cover = $data['image_path'];
        $articleModel->is_top = isset($data['is_top'])? 1 : 0;
        $articleModel->author = $author;
        $articleModel->markdown = $data['markdown'];
        $articleModel->html = $data['html'];
        if($articleModel->save()){
            $data = ['code' => 200,'message' => '数据添加成功'];
        }else{
            $data = ['code' => 400,'message' => '数据添加失败'];
        }
        return $data;
    }

    /**
     * 数据详情
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * 修改页面展示
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('admin.article.edit');
    }

    /**
     * 修改数据
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * 删除数据
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * 图片上传
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public  function uploadImage(Request $request)
    {
        //保存文件路径
        $savePath = DIRECTORY_SEPARATOR.'uploads';
        $file = $request->file('image_file');
        //判断文件是否存在
        if(!$request->hasFile('image_file')){
            $data = [ 'code' => 401,'message' => '上传文件为空'];
            return $data;
        }
        //文件上传是否有效
        if(!$file->isValid()){
            $data = ['code' => 500,'message' => '文件上传出错'];
            return $data;
        }
        //获取文件扩展名
        $ext = $file->getClientOriginalExtension();
        //获取文件路径
        $path = $file->getRealPath();
        //文件保存名称
        $fileName = sha1(time().rand(1000,9999)).'.'.$ext;
        //将缓存文件移动到指定文件夹
        $dirPath = public_path().$savePath;
        //如果目录不存在，先创建目录
        is_dir($dirPath) || mkdir($dirPath,0755,true);
        //定义文件上传类型
        $allowExtension = ['jpg','png','jpeg','bmp'];
        if(!empty($allowExtension) && !in_array($ext,$allowExtension)){
            $data = ['code' => 500,'message' => $ext.'的文件类型不被允许'];
            return $data;
        }
        //保存文件
        if(!$file->move($dirPath,$fileName)){
            $data = ['code' => 500,'message' => '保存文件失败'];
        }else{
            $data = [
                'code' => 200,
                'message' => '文件上传成功',
                'data'=>[
                    'path'=> $savePath.DIRECTORY_SEPARATOR.$fileName
                ]
            ];
        }
        return $data;
    }
}
