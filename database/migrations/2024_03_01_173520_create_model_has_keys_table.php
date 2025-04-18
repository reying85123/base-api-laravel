<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModelHasKeysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('model_has_keys', function (Blueprint $table) {
            $table->unsignedBigInteger('key_id')->comment('金鑰ID');
            $table->string('model_type');
            $table->uuid('model_id');
            $table->index(['model_id', 'model_type'], 'model_has_keys_model_id_model_type_index');

            $table->foreign('key_id')
                ->references('id')
                ->on('keys')
                ->onDelete('cascade');

            $table->primary(['key_id', 'model_id', 'model_type'], 'model_has_keys_key_model_type_primary');
        });
        \DB::statement("ALTER TABLE `model_has_keys` comment 'model有金鑰'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('model_has_keys');
    }
}
