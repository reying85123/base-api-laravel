<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRelationshipsTable extends Migration
{
    public function up()
    {
        Schema::create('relationships', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();

            $table->uuid('entity_id')->comment('實體的ID');
            $table->string('entity_type')->comment('實體的類型');

            $table->uuid('related_entity_id')->comment('關聯的實體ID');
            $table->string('related_entity_type')->comment('關聯實體的類型');

            $table->string('group')->nullable()->comment('群組');

            $table->date('start_date')->nullable()->comment('開始日期');
            $table->date('end_date')->nullable()->comment('結束日期');
            $table->enum('priority', ['high', 'medium', 'low'])->nullable()->comment('優先順序: high => 高; medium => 中; low => 低');
            $table->text('remarks')->nullable()->comment('備註');

            $table->enum('direction_type', ['single', 'mutual'])->default('single')->comment('方向類型: single => 單向; mutual => 雙向;');
        });
        \DB::statement("ALTER TABLE `relationships` comment '關係'");
    }

    public function down()
    {
        Schema::dropIfExists('relationships');
    }
}
