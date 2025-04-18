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
            $table->uuid('id')->primary();
            $table->timestamps();
            $table->softDeletes();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('account')->comment('帳號');
            $table->string('password');
            $table->rememberToken();

            $table->boolean('is_enable')->default(1)->comment('是否啟用此帳號');
            $table->boolean('is_admin')->default(0)->comment('是否為管理者');
            $table->boolean('is_superadmin')->default(0)->comment('是否為超級管理者(開發者)');
            $table->text('remark')->nullable()->comment('人員備註');
        });

        \DB::statement("ALTER TABLE `users` comment '人員管理'");
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
