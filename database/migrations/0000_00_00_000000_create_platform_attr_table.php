<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlatformAttrTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('platform_attributes', function (Blueprint $table) {
            $table->string('name')->primary()->comment('參數名稱');
            $table->text('value')->comment('參數的值');
            $table->string('description')->nullable()->comment('參數描述');
        });

        \DB::statement("ALTER TABLE `platform_attributes` comment '平台參數管理'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('platform_attributes');
    }
}