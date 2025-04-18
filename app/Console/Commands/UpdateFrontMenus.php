<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Modules\FrontendMenu\Models\FrontendMenu;

use Spatie\Permission\Models\Permission;

use Illuminate\Support\Str;

use Illuminate\Support\Facades\DB;

class UpdateFrontMenus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:frontMenus {--permission}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '更新前台選單';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $updateMenus = collect(self::getUpdateMenus());

        if ($updateMenus->count() === 0) {
            echo '無需更新' . PHP_EOL;
            return 0;
        }

        DB::beginTransaction();

        //移除不在前端功能清單的功能
        FrontendMenu::whereNotIn('key', $updateMenus->pluck('key')->toArray())->delete();

        //新增\更新前端功能資訊
        $updateMenus->each(function ($menu) {
            FrontendMenu::updateOrInsert(
                ['id' => $menu['id']],
                collect($menu)->only(['id', 'name', 'key', 'type', 'link', 'sequence'])->merge(['parent_id' => null, 'updated_at' => getDateTime(), 'created_at' => getDateTime()])->toArray(),
            );

            if (!!$menuParentKey = $menu['parent_key'] ?? null) {
                $parentMenu = FrontendMenu::where('key', $menuParentKey)->first();
                if (!!$parentMenu) {
                    FrontendMenu::where(['id' => $menu['id']])->update([
                        'parent_id' => $parentMenu->id,
                    ]);
                }
            }
        });

        $permissions = $updateMenus->where('is_new', true)->map(function ($menu) {
            return collect($menu['permissions'])->map(function ($permission) use ($menu) {
                $permission['menus_id'] = $menu['id'];
                return $permission;
            });
        })->flatten(1)->toArray();

        Permission::insert($permissions);

        if ($this->option('permission')) {
            //更新功能權限
            $allPermissions = collect($updateMenus)
                ->pluck('permissions', 'id')
                ->flatMap(function ($permissions, $menuId) {
                    return collect($permissions)->map(function ($permission) use ($menuId) {
                        return $permission + ['menus_id' => $menuId];
                    });
                });
            $newPermissions = $allPermissions->whereNotIn('name', Permission::where(['guard_name' => 'client_api'])->get()->pluck('name'))->values();

            //新增功能權限
            Permission::insert($newPermissions->toArray());

            //移除未使用功能權限
            Permission::where(['guard_name' => 'client_api'])->whereNotIn('name', $allPermissions->pluck('name'))->delete();
        }

        DB::commit();

        $this->info('前端功能更新完成');

        return 0;
    }

    /**
     * 取得更新前端功能
     *
     * @param integer $index
     * @return array
     */
    private static function getUpdateMenus($index = 0)
    {
        $nowMenus = json_decode(file_get_contents(resource_path('/system_data/frontend_menus.json')), true);

        if (!$nowMenus) {
            throw new \Exception('資料錯誤');
        }

        if (collect($nowMenus)->whereNull('key')->count() > 0) {
            throw new \Exception('前端功能key不可為空');
        }

        if (collect($nowMenus)->unique('key')->count() !== count($nowMenus)) {
            throw new \Exception('前端功能key不可重複');
        }

        $dbMenus = FrontendMenu::select(['id', 'key'])->get();

        $nowMenus = collect($nowMenus)->reduce(function ($now, $menu) use ($dbMenus, &$index) {
            $uuid = Str::uuid();
            $isNew = true;

            if (empty($menu['key'])) {
                throw new \Exception('key不可為空');
            }

            if ($menu['key'] === ($menu['parent_key'] ?? null)) {
                throw new \Exception('父層key不可為自己');
            }

            if ($dbMenus->where('key', $menu['key'])->isNotEmpty()) {
                $uuid = $dbMenus->where('key', $menu['key'])->first()->id;
                $isNew = false;
            }

            $now[] = $menu + ['id' => $uuid, 'sequence' => $index++, 'is_new' => $isNew];
            return $now;
        }, []);

        return $nowMenus;
    }
}
