<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBrowserHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('browser_histories', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('device_type')->nullable()->comment('裝置種類');
            $table->string('platform')->nullable()->comment('裝置系統');
            $table->string('sourceip')->nullable()->comment('來源IP');
            $table->string('browser')->nullable()->comment('瀏覽器');
            $table->boolean('is_robot')->default(0)->comment('是否機器人');
            $table->string('robot_name')->nullable()->comment('機器人名稱');
            $table->text('link')->nullable()->comment('連結');
            $table->integer('sequence')->default(0)->comment('排序');
        });
        \DB::statement("ALTER TABLE `browser_histories` comment '瀏覽紀錄'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('browser_histories');
    }
}
