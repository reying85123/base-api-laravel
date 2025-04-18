<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLanguageContentFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('language_content_forms', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->string('name')->comment('名稱');
            $table->string('usage_type')->comment('使用類別');
            $table->boolean('is_enable')->default(1)->comment('是否啟用');
            $table->integer('sequence')->default(0)->comment('排序');
        });

        \DB::statement("ALTER TABLE `language_content_forms` comment '多語系功能內容表單'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('language_content_forms');
    }
}
