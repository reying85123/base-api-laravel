<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserNotifiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_notifies', function (Blueprint $table) {
            $table->timestamps();
            $table->uuid('uuid')->primary()->comment('UUID');
            $table->foreignUuid('user_id')->comment('人員ID')->references('id')->on('users');
            $table->string('title')->comment('標題');
            $table->text('description')->comment('內容描述');
            $table->boolean('is_read')->default(0)->comment('已/未讀，0 => 未讀、1 => 已讀');
        });

        \DB::statement("ALTER TABLE `user_notifies` comment '後台人員通知'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_notifies');
    }
}
