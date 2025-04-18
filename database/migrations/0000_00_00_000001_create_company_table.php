<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateCompanyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->foreignUuid('created_user_id')->nullable()->comment('建立人員ID');
            $table->string('created_user_name')->nullable()->comment('建立人員名稱');
            $table->foreignUuid('updated_user_id')->nullable()->comment('更新人員ID');
            $table->string('updated_user_name')->nullable()->comment('更新人員名稱');
            $table->foreignUuid('deleted_user_id')->nullable()->comment('刪除人員ID');
            $table->string('deleted_user_name')->nullable()->comment('刪除人員名稱');
            $table->string('name')->comment('公司名稱');
            $table->foreignId('parent_id')->nullable()->comment('父層公司ID');
            $table->string('name_en')->nullable()->comment('公司名稱(英)');
            $table->string('opendate')->nullable()->comment('成立時間');
            $table->string('invoice')->nullable()->comment('發票抬頭');
            $table->string('vatnumber')->nullable()->comment('統一編號');
            $table->string('ceo')->nullable()->comment('負責人');
            $table->string('tel')->nullable()->comment('主要電話');
            $table->string('tel_ext')->nullable()->comment('分機');
            $table->string('tel_service')->nullable()->comment('客服專線');
            $table->string('fax')->nullable()->comment('傳真');
            $table->string('phone')->nullable()->comment('手機');
            $table->string('email')->nullable()->comment('連絡信箱');
            $table->string('post_code')->nullable()->comment('郵遞區號');
            $table->unsignedBigInteger('city_id')->nullable()->comment('縣市ID')->references('id')->on('commons');
            $table->unsignedBigInteger('area_id')->nullable()->comment('行政區ID')->references('id')->on('commons');
            $table->string('address')->nullable()->comment('地址');
            $table->string('address_en')->nullable()->comment('地址(英)');
            $table->string('service_time')->nullable()->comment('客服時間');
            $table->integer('sequence')->default(0)->comment('排序');
            $table->string('lan', 10)->nullable()->comment('語系');
        });

        Schema::create('company_jobs', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->string('name')->comment('職稱名稱');
            $table->foreignId('parent_id')->nullable()->comment('上層職稱ID')->references('id')->on('company_jobs');
            $table->integer('sequence')->default(0)->comment('排序');
            $table->string('lan', 10)->nullable()->comment('語系');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('company_id')->nullable()->comment('公司ID')->references('id')->on('companies');
            $table->foreignId('company_job_id')->nullable()->comment('公司職稱ID')->references('id')->on('company_jobs');
        });

        Schema::table('companies', function (Blueprint $table) {
            $table->foreign('parent_id')->on('companies')->references('id')->cascadeOnDelete();
        });

        \DB::statement("ALTER TABLE `companies` comment '公司'");
        \DB::statement("ALTER TABLE `company_jobs` comment '公司職稱'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('users_company_id_foreign');
            $table->dropColumn('company_id');
            $table->dropForeign('users_company_job_id_foreign');
            $table->dropColumn('company_job_id');
        });

        Schema::dropIfExists('companies');
        Schema::dropIfExists('company_jobs');
    }
}
