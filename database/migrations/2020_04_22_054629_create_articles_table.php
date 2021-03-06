<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id()->comment('文章表主键');
            $table->unsignedTinyInteger('category_id')->default(0)->comment('分类id');
            $table->string('title')->default('')->comment('标题');
            $table->string('slug')->default('')->nullable()->comment('sulg');
            $table->string('author')->default('')->comment('作者');
            $table->mediumText('markdown')->nullable()->comment('markdown文章内容');
            $table->mediumText('html')->nullable()->comment('markdown转的html页面');
            $table->string('description')->default('')->nullable()->comment('描述');
            $table->string('keywords')->default('')->nullable()->comment('关键词');
            $table->string('cover')->default('')->nullable()->comment('封面图');
            $table->unsignedTinyInteger('is_top')->default(0)->comment('是否置顶 1是 0否');
            $table->integer('views')->unsigned()->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articels');
    }
}
