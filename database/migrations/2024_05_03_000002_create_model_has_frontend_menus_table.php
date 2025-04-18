<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModelHasFrontendMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('model_has_frontend_menus', function (Blueprint $table) {
            $table->uuid('frontend_menu_id')->comment('前台菜單ID');
            $table->string('model_type');
            $table->uuid('model_id');
            $table->index(['model_id', 'model_type'], 'model_has_frontend_menus_model_id_model_type_index');

            $table->foreign('frontend_menu_id')->references('id')->on('frontend_menus')->onDelete('cascade');

            $table->primary(['frontend_menu_id', 'model_id', 'model_type'], 'model_has_frontend_menus_frontend_menu_model_type_primary');
        });
        \DB::statement("ALTER TABLE `model_has_frontend_menus` comment 'model有前台菜單'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('model_has_frontend_menus');
    }
}
