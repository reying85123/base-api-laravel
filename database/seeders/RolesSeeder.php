<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = Permission::all();

        DB::beginTransaction();

        $superAdmin = Role::create([
            'name' => '超級管理員',
            'guard_name' => 'api',
            'is_superadmin' => true,
        ]);

        $admin = Role::create([
            'name' => '管理員',
            'guard_name' => 'api',
        ]);
        $superAdmin->syncPermissions($permissions);

        DB::commit();
    }
}
