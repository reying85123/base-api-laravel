<?php

use Illuminate\Database\Migrations\Migration;

use Modules\User\Models\User;
use Modules\Company\Models\Company;
use Spatie\Permission\Models\Role;

use Illuminate\Support\Facades\Hash;

class SetupRoleUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //1. 建立角色群組(超級管理員、管理員)
        Role::create([
            'name' => '超級管理員',
            'guard_name' => 'api',
            'is_superadmin' => true,
        ]);

        Role::create([
            'name' => '管理員',
            'guard_name' => 'api',
        ]);

        //2. 建立公司
        $company = Company::create([
            'name' => '總公司',
        ]);

        //3. 建立系統帳號(管理員、開發者)
        //建立engineer開發者帳號
        $engineer = new User;
        $engineer->account = 'engineer';
        $engineer->is_superadmin = true;
        $engineer->fill([
            'name' => 'engineer管理者',
            'email' => 'engineer@engineer.com.tw',
            'password' => Hash::make('qweasdzxcC@'),
        ])->save();

        $engineer->assignRole('超級管理員');
        $engineer->company()->associate($company)->save();

        //建立網站管理員帳號
        $user = new User;
        $user->account = 'admin';
        $user->is_admin = true;
        $user->fill([
            'name' => '網站管理員',
            'email' => 'admin@admin.com',
            'account' => 'admin',
            'password' => Hash::make('qazwsxedcC@'),
        ])->save();

        $user->assignRole('管理員');
        $user->company()->associate($company)->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        User::query()->delete();
        Role::query()->delete();
    }
}
