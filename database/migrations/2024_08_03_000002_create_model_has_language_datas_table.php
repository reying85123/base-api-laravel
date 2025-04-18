<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModelHasLanguageDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('model_has_language_datas', function (Blueprint $table) {
            $table->unsignedBigInteger('language_data_id')->comment('語系資料ID');
            $table->string('model_type');
            $table->uuid('model_id');
            $table->index(['model_id', 'model_type'], 'model_has_language_datas_model_id_model_type_index');

            $table->foreign('language_data_id')
                ->references('id')
                ->on('language_datas')
                ->onDelete('cascade');

            $table->primary(['language_data_id', 'model_id', 'model_type'], 'model_has_language_datas_key_model_type_primary');
        });
        \DB::statement("ALTER TABLE `model_has_language_datas` comment 'model有語系資料'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('model_has_language_datas');
    }
}
