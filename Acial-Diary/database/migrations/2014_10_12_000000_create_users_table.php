<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id()->comment('登録者ID');
            $table->string('name', 50)->comment('登録者名');
            $table->string('email', 100)->unique()->comment('メールアドレス');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 100)->comment('パスワード');
            $table->rememberToken()->comment('パスワード再発行トークン');
            $table->timestamps();
            $table->tinyInteger('active')->default(1)->comment('利用可能フラグ');
            $table->tinyInteger('type')->default(1)->comment('ユーザータイプ');
            $table->string('icon')->nullable()->comment('アイコン');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
