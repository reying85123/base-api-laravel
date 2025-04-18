<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMailLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mail_logs', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('from')->comment('寄件人信箱');
            $table->string('to')->comment('收件人信箱');
            $table->string('cc')->nullable()->comment('副本信箱');
            $table->string('bcc')->nullable()->comment('密件副本信箱');
            $table->string('reply_to')->nullable()->comment('回覆信箱');
            $table->string('subject')->comment('信件主旨');
            $table->dateTime('send_datetime')->comment('信件發送時間');
            $table->mediumText('content')->comment('信件內容(HTML)');
            $table->enum('state', ['success', 'failed'])->comment('發送狀態');

            $table->mediumText('error_message')->nullable()->comment('錯誤訊息');
        });

        \DB::statement("ALTER TABLE `mail_logs` comment '信件發送紀錄'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mail_logs');
    }
}
