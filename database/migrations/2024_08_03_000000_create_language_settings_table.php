<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLanguageSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('language_settings', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->string('name')->comment('名稱');
            $table->string('locale')->unique()->comment('語系');
            $table->boolean('is_enable')->default(1)->comment('是否啟用');
            $table->boolean('is_client_enable')->default(1)->comment('前台是否啟用');
            $table->boolean('is_admin_enable')->default(1)->comment('後台是否啟用');
            $table->boolean('is_default')->default(0)->comment('是否預設');
            $table->integer('sequence')->default(0)->comment('排序');
        });

        \DB::table('language_settings')->insert([
            ['locale' => 'zh-TW', 'name' => '繁體中文', 'is_enable' => true, 'is_client_enable' => true, 'is_admin_enable' => true, 'is_default' => true],
            ['locale' => 'en', 'name' => '英文', 'is_enable' => false, 'is_client_enable' => false, 'is_admin_enable' => false, 'is_default' => false],
        ]);

        \DB::statement("ALTER TABLE `language_settings` comment '語系設定'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('language_settings');
    }
}
