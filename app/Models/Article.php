<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    //指定表名
    public $table = 'articles';
    //指定主键
    public $primaryKey = 'id';
    //自动更新时间戳
    public $timestamps = true;
    //不允许赋值的字段
    protected $guarded = [];

}
