<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//admin模块
Route::namespace('Admin')->prefix('admin')->group(function (){
    //首页
    Route::prefix('index')->group(function(){
        Route::get('index','IndexController@index');
    });
    //文章
    Route::prefix('article')->group(function(){
        //列表页面
        Route::get('index','ArticleController@index');
        //获取列表数据
        Route::get('getListDatas','ArticleController@getListDatas');
        //添加页面
        Route::get('create','ArticleController@create');
        //添加
        Route::post('store','ArticleController@store');
        //编辑页面
        Route::get('edit/{id}','ArticleController@edit');
        //编辑
        Route::post('update/{id}','ArticleController@update');
        //删除
        Route::get('destroy/{id}','ArticleController@destroy');
        //文件上传
        Route::post('uploadImage','ArticleController@uploadImage');
    });
});

