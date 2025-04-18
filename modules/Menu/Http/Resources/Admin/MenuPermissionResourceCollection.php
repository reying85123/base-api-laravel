<?php

namespace Modules\Menu\Http\Resources\Admin;

use App\Enums\PermissionExtraNameEnum;

use Illuminate\Http\Resources\Json\ResourceCollection;

class MenuPermissionResourceCollection extends ResourceCollection
{
    protected $menus;

    public function setMenus($menus)
    {
        $this->menus = $menus;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $permissionExtraNameSelect = collect(PermissionExtraNameEnum::toSelectArray());

        return $this->collection->map(function ($menu) use ($permissionExtraNameSelect) {
            $childMenuResource = [];
            $childs = $this->menus->where('parent_id', $menu->id);

            if ($childs->isNotEmpty()) {
                $childMenuResource = new MenuPermissionResourceCollection($childs);
                $childMenuResource->setMenus($this->menus);
            }

            return [
                'id' => $menu->id,
                'name' => $menu->name,
                'permissions' => $menu->permissions->map(function ($menuPermission) use ($permissionExtraNameSelect) {
                    return collect($menuPermission)
                        ->only('id', 'name', 'action')
                        ->merge(['display_name' => trans('permissions.' . $menuPermission['action'], ['name' => $permissionExtraNameSelect->get($menuPermission->name, '')])]);
                }),
                'childs' => $childMenuResource,
            ];
        });
    }
}
