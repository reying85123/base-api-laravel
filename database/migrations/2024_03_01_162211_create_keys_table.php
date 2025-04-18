<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKeysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('keys', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->string('prefix')->comment('前綴');
            $table->string('code')->comment('code');
            $table->string('value')->comment('值');
            $table->boolean('is_enable')->default(1)->comment('是否啟用');
            $table->integer('sequence')->default(0)->comment('排序');
        });

        \DB::statement("ALTER TABLE `keys` comment '金鑰'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('keys');
    }
}
