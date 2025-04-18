<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLanguageContentFormFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('language_content_form_fields', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('language_content_form_id')->comment('多語系功能內容表單ID');
            $table->string('value_type')->comment('欄位型態');
            $table->string('data_key')->nullable()->comment('dataKey');
            $table->foreignId('parent_id')->nullable()->comment('父層ID');
            $table->string('component')->nullable()->comment('使用元件');
            $table->string('i18n_key')->nullable()->comment('多語系Key');
            $table->string('label')->nullable()->comment('標籤');
            $table->boolean('required')->default(0)->comment('必填');
            $table->boolean('disable')->default(0)->comment('禁用');
            $table->boolean('readonly')->default(0)->comment('唯獨');
            $table->boolean('is_show')->default(1)->comment('是否顯示');
            $table->decimal('xs', 10, 0)->nullable()->comment('xs');
            $table->decimal('sm', 10, 0)->nullable()->comment('sm');
            $table->decimal('md', 10, 0)->nullable()->comment('md');
            $table->decimal('lg', 10, 0)->nullable()->comment('lg');
            $table->decimal('xl', 10, 0)->nullable()->comment('xl');
            $table->string('placeholder')->nullable()->comment('placeholder');
            $table->integer('sequence')->default(0)->comment('排序');
        });

        \DB::statement("ALTER TABLE `language_content_form_fields` comment '多語系功能內容表單欄位'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('language_content_form_fields');
    }
}
