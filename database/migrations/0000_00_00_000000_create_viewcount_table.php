<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateViewcountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('viewcount', function(Blueprint $table){
            $table->id();
            $table->timestamps();
            $table->string('sourceip')->comment('來源IP');
        });

        \DB::statement("ALTER TABLE `viewcount` comment '網站瀏覽者紀錄'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('viewcount');
    }
}
