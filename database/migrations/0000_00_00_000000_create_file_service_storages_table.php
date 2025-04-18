<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFileServiceStoragesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('file_storages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->timestamps();
            $table->softDeletes();
            $table->string('origin_name')->nullable()->comment('上傳原檔名');
            $table->string('file_storage_name')->comment('存儲於系統中的名稱');
            $table->string('path')->comment('存儲於系統中的位置(位於storage底下)');
            $table->text('file_json')->comment('File物件(JSON)');
            $table->string('driver')->comment('上傳時的Laravel檔案驅動');
            $table->dateTime('without_using_datetime')->nullable()->comment('檔案不被使用的時間點');
        });

        Schema::create('file_storage_target', function (Blueprint $table) {
            $table->foreignUuid('file_storage_id')->comment('檔案管理uuid')->references('id')->on('file_storages');
            $table->unsignedBigInteger('target_id')->comment('關聯目標id');
            $table->string('target_type')->comment('關聯目標類型');
            $table->string('tag')->nullable()->comment('自定義標籤，區分關聯同筆資料，不同性質檔案');
        });

        \DB::statement("ALTER TABLE `file_storages` comment '上傳檔案管理'");
        \DB::statement("ALTER TABLE `file_storage_target` comment '上傳檔案管理關聯表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('file_storage_target');
        Schema::dropIfExists('file_storages');
    }
}
