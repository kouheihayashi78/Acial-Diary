<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id()->comment('投稿ID');
            $table->string('title', 50)->comment('投稿タイトル');
            $table->string('body')->comment('投稿内容');
            $table->string('img')->nullable()->comment('投稿画像');
            $table->unsignedBigInteger('user_id')->comment('投稿したユーザー');
            $table->timestamps();
            $table->tinyInteger('active')->default(1)->comment('利用可能フラグ');
            $table->tinyInteger('publish')->default(1)->comment('公開フラグ');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
}
