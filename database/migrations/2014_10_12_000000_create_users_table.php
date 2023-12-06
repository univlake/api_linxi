<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique()->comment('登录用户名');
            $table->string('nickname', 100)->unique()->comment('姓名');
            $table->string('password');
            $table->string('tel')->unique()->nullable()->comment('手机号');
            $table->string('header_img')->default(public_path('./images/user.jpg'))->comment('头像');
            $table->string('email')->nullable()->comment('邮箱');
            $table->integer('role_id')->default(1)->index()->comment('角色');
            $table->integer('status')->default(1)->index()->comment('账号状态');
            $table->integer('user_id')->default(0)->index()->comment('创建人id');
            $table->integer('last_user_id')->default(0)->index()->comment('最后修改人id');
            $table->string('login_times')->nullable()->comment('最后登陆时间');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
