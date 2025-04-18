<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataAccessRoleTargetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_access_role_targets', function (Blueprint $table) {
            $table->timestamps();

            $table->foreignId('data_access_role_id')->comment('資料權限群組ID');

            $table->foreignId('target_id')->comment('關聯ID');
            $table->string('target_type')->comment('關聯類型');

            $table->primary(['data_access_role_id', 'target_type', 'target_id'], 'data_access_role_targets_data_access_role_id_target_type_id');
        });

        Schema::table('data_access_role_targets', function (Blueprint $table) {
            $table->foreign('data_access_role_id')->references('id')->on('data_access_roles')->cascadeOnDelete();
        });

        \DB::statement("ALTER TABLE `data_access_role_targets` comment '資料控管權限群組<->關聯資料'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('data_access_role_targets');
    }
}
