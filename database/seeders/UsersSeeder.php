<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Modules\User\Models\User;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::beginTransaction();

        //建立engineer開發者帳號
        $engineer = User::create([
            'name' => 'engineer管理者',
            'email' => 'engineer@engineer.com.tw',
            'account' => 'engineer',
            'password' => Hash::make('qweasdzxcC@'),
            'is_superadmin' => true,
        ]);

        $engineer->assignRole('管理員');

        //建立網站管理員帳號
        $user = User::create([
            'name' => '網站管理員',
            'email' => 'admin@admin.com',
            'account' => 'admin',
            'password' => Hash::make('qazwsxedcC@'),
            'is_admin' => true,
        ]);

        $user->assignRole('管理員');

        DB::commit();
    }
}
