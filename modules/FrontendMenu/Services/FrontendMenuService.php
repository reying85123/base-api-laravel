<?php

namespace Modules\FrontendMenu\Services;

use Modules\FrontendMenu\Models\FrontendMenu;

use Modules\FrontendMenu\Services\FrontendMenuItemService;

use App\Abstracts\Services\AbstractModelService;

/**
 * @method FrontendMenu getModel()
 * @property FrontendMenu $model
 */
class FrontendMenuService extends AbstractModelService
{

    protected $frontendMenuItemService;

    public function __construct(FrontendMenu $model)
    {
        $this->setModel($model);
    }

    /**
     * 關聯Parent
     *
     * @param int $frontendMenuId
     * @return static
     */
    public function associateParent($frontendMenuId)
    {
        $model = !!$frontendMenuId ? FrontendMenu::findOrFail($frontendMenuId) : null;
        return $this->model->parent()->associate($model);
    }

    public function updateItems(array $updateItems)
    {
        $this->getFrontendMenuItemService()->updateItems($updateItems, $this->model);
    }

    private function getFrontendMenuItemService()
    {
        if (!$this->frontendMenuItemService) {
            $this->frontendMenuItemService = app(FrontendMenuItemService::class);
        }
        return $this->frontendMenuItemService;
    }
}
