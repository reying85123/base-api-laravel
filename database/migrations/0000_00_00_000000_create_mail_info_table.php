<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMailInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mailinfo', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name')->comment('信件名稱');
            $table->string('subject')->comment('信件主旨');
            $table->string('fromname')->nullable()->comment('寄件者名稱');
            $table->string('repeatname')->nullable()->comment('回覆者名稱');
            $table->string('repeatmail')->nullable()->comment('回覆者信箱');
            $table->string('cc')->nullable()->comment('副本信箱，多個信箱以,分隔');
            $table->string('bcc')->nullable()->comment('密件副本信箱，多個信箱以,分隔');
            $table->string('tomail')->comment('收件者信箱');
            $table->string('identification')->comment('信件識別唯一(程式碼用)');
            $table->mediumText('content_json')->nullable()->comment('信件內容(JSON)');
            $table->mediumText('content')->nullable()->comment('信件內容(HTML)');
        });

        Schema::create('mailinfo_displace_text', function (Blueprint $table) {
            $table->foreignId('mailinfo_id')->comment('信件樣板ID')->references('id')->on('mailinfo');
            $table->string('text')->comment('替換文字');
            $table->string('description')->comment('替換文字描述');
        });

        \DB::statement("ALTER TABLE `mailinfo` comment '信件樣板'");
        \DB::statement("ALTER TABLE `mailinfo_displace_text` comment '信件樣板-替代文字'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mailinfo_displace_text');
        Schema::dropIfExists('mailinfo');
    }
}
