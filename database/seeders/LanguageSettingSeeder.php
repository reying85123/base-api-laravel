<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Modules\Language\Models\LanguageSetting;

use Illuminate\Support\Facades\DB;

class LanguageSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::beginTransaction();

        LanguageSetting::create([
            'name' => '英文',
            'locale' => 'en',
        ]);
        LanguageSetting::create([
            'name' => '繁體中文',
            'locale' => 'zh-TW',
        ]);

        DB::commit();
    }
}
