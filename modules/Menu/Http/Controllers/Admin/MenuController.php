<?php

namespace Modules\Menu\Http\Controllers\Admin;

use Modules\Menu\Models\Menu;

use Modules\UserAuth\Services\UserAuthService;

use Modules\Menu\Http\Resources\Admin\MenuResourceCollection;
use Modules\Menu\Http\Resources\Admin\MenuPermissionResourceCollection;

use App\Http\Controllers\Controller;

use Jiannei\Response\Laravel\Support\Facades\Response;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $menus = Menu::with(['childs'])->get();
        $menuResource = new MenuResourceCollection($menus->where('parent_id', null));
        $menuResource->setMenus($menus);

        return Response::success($menuResource);
    }

    public function getPermission()
    {
        $menus = Menu::with(['childs', 'permissions'])->get();
        $permissions = UserAuthService::toUser()->getAllPermissions();

        $menus = $menus->map(function ($menu) use ($permissions) {
            $menu['permissions'] = collect($menu['permissions'])->filter(function ($permission) use ($permissions) {
                return $permissions->contains('name', $permission['name']);
            });
            $menu['childs'] = collect($menu['childs'])->map(function ($child) use ($permissions) {
                $child['permissions'] = collect($child['permissions'])->filter(function ($permission) use ($permissions) {
                    return $permissions->contains('name', $permission['name']);
                });
                return $child;
            });
            return $menu;
        })->filter(function ($menu) {
            return $menu['permissions']->count() > 0;
        });

        $menuPermissionResource = new MenuPermissionResourceCollection($menus->where('parent_id', null));
        $menuPermissionResource->setMenus($menus);

        return Response::success($menuPermissionResource);
    }
}
