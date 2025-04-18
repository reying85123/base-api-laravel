<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFileReferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('file_references', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();

            $table->string('model_type');
            $table->uuid('model_id');
            $table->string('title')->nullable()->comment('標題');
            $table->string('tag')->nullable()->comment('自定義標籤，區分關聯同筆資料，不同性質檔案');
            $table->integer('sequence')->default(0)->comment('排序');

            $table->index(['model_id', 'model_type'], 'model_has_keys_model_id_model_type_index');
        });

        \DB::statement("ALTER TABLE `file_references` comment '上傳檔案跟功能的關聯表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('file_references');
    }
}
