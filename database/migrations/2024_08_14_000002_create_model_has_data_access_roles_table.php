<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModelHasDataAccessRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('model_has_data_access_roles', function (Blueprint $table) {
            $table->unsignedBigInteger('data_access_role_id')->comment('資料權限群組ID');
            $table->string('model_type');
            $table->uuid('model_id');
            $table->index(['model_id', 'model_type'], 'model_has_data_access_roles_model_id_model_type_index');

            $table->foreign('data_access_role_id')
                ->references('id')
                ->on('data_access_roles')
                ->onDelete('cascade');

            $table->primary(['data_access_role_id', 'model_id', 'model_type'], 'model_has_data_access_roles_data_access_role_model_type_primary');
        });
        \DB::statement("ALTER TABLE `model_has_data_access_roles` comment 'model有資料權限群組'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('model_has_data_access_roles');
    }
}
