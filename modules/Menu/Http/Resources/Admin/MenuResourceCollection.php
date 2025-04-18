<?php

namespace Modules\Menu\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\ResourceCollection;

class MenuResourceCollection extends ResourceCollection
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
        return $this->collection->map(function($menu){
            $childMenuResource = [];
            $childs = $this->menus->where('parent_id', $menu->id);

            if($childs->isNotEmpty()){
                $childMenuResource = new MenuResourceCollection($childs);
                $childMenuResource->setMenus($this->menus);
            }

            return [
                'id' => $menu->id,
                'name' => $menu->name,
                'slug' => $menu->slug,
                'childs' => $childMenuResource,
            ];
        });
    }
}
