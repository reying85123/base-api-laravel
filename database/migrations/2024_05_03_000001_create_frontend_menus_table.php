<?php

use Modules\FrontendMenu\Enums\FrontendMenuTypeEnum;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFrontendMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('frontend_menus', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->timestamps();
            $table->softDeletes();
            $table->string('name')->comment('菜單名稱');
            $table->string('key')->nullable()->comment('功能唯一辨識值');
            $table->foreignUuid('parent_id')->nullable()->comment('父層菜單ID');
            $table->enum('type', FrontendMenuTypeEnum::getValues())->nullable()->comment('頁面類型');
            $table->string('icon')->nullable()->comment('功能icon');
            $table->text('link')->nullable()->comment('連結');
            $table->boolean('is_link_blank')->default(0)->comment('是否另開連結');
            $table->boolean('is_enable')->default(1)->comment('是否啟用');
            $table->integer('sequence')->default(0)->comment('排序');
        });

        Schema::table('frontend_menus', function (Blueprint $table) {
            $table->foreign('parent_id')->on('frontend_menus')->references('id')->onDelete('cascade');
        });

        \DB::statement("ALTER TABLE `frontend_menus` comment '前台菜單'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('frontend_menus');
    }
}
