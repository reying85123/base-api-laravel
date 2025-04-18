<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSystemLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_logs', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignUuid('user_id')->comment('人員ID')->references('id')->on('users');
            $table->string('title')->comment('標題');
            $table->string('description')->comment('操作描述');
            $table->string('sourceip')->comment('來源IP');
            $table->string('type')->comment('操作類型');
        });

        \DB::statement("ALTER TABLE `system_logs` comment '管理員日誌'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('system_logs');
    }
}
