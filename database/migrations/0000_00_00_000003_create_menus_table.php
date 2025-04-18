<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->timestamps();
            $table->uuid('id')->primary();
            $table->string('name')->comment('功能名稱');
            $table->string('key')->comment('功能唯一辨識值');
            $table->string('href')->nullable()->comment('功能連結');
            $table->string('icon')->nullable()->comment('功能icon');
            $table->string('slug')->comment('顯示於功能列型態');
            $table->enum('type', ['front', 'back'])->comment('功能位於系統中的位置，front => 前台、back => 後台');
            $table->integer('sequence')->default(0)->comment('排序');

            $table->unique('key');
        });

        Schema::table('menus', function (Blueprint $table) {
            $table->foreignUuid('parent_id')->nullable()->after('id')->comment('上層功能UUID')->references('id')->on('menus');
        });

        Schema::table('permissions', function (Blueprint $table) {
            $table->string('action')->comment('功能動作');
            $table->uuid('menus_id')->comment('功能UUID');
        });

        \DB::statement("ALTER TABLE `menus` comment '功能菜單管理'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('permissions', function (Blueprint $table) {
            if (Schema::hasColumn('permissions', 'action')) $table->dropColumn(['action']);

            if (Schema::hasColumn('permissions', 'menus_id')) {
                $table->dropForeign('permissions_menus_id_foreign');
                $table->dropColumn('menus_id');
            }
        });

        Schema::dropIfExists('menus');
    }
}
