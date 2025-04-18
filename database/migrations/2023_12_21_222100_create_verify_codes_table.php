<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVerifyCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('verify_codes', function (Blueprint $table) {

            $table->id();
            $table->timestamps();
            $table->enum('type', ['register', 'login', 'forget_password'])->nullable()->comment('發送類型');
            $table->string('token')->comment('驗證碼');
            $table->string('account')->nullable()->comment('帳號');
            $table->string('email')->nullable()->comment('信箱');
            $table->string('phone')->nullable()->comment('手機');
            $table->string('ip')->nullable()->comment('ip');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('verify_codes');
    }
}
