<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBaseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('serial_no', function(Blueprint $table){
            $table->id();
            $table->string('no_type')->comment('流水號開頭');
            $table->string('year_month_date')->comment('流水號日期');
            $table->integer('seq_no', false, true)->comment('流水號');
        });

        Schema::create('commons', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->string('common_group')->comment('通用資料的群組');
            $table->string('name')->comment('通用資料的顯示名稱');
            $table->foreignId('parent_id')->nullable()->constrained('commons')->comment('此項目的父層');
            $table->string('value')->nullable()->comment('通用資料的值');
        });

        \DB::statement("ALTER TABLE `serial_no` comment '流水號記錄'");
        \DB::statement("ALTER TABLE `commons` comment '通用資料表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('serial_no');
        Schema::dropIfExists('commons');
    }
}
