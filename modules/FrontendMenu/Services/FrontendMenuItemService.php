<?php

namespace Modules\FrontendMenu\Services;

use Modules\FrontendMenu\Models\FrontendMenuItem;

use App\Abstracts\Services\AbstractModelService;

use Illuminate\Support\Arr;

/**
 * @method FrontendMenuItem getModel()
 * @property FrontendMenuItem $model
 */
class FrontendMenuItemService extends AbstractModelService
{
    public function __construct(FrontendMenuItem $model)
    {
        $this->setModel($model);
    }

    /**
     * 更新 Items
     *
     * @param array $updateItems
     * @param $relatedModle
     */
    public function updateItems(array $updateItems = [], $relatedModle)
    {
        $items = FrontendMenuItem::where('frontend_menu_id', $relatedModle->id);
        $updateIds = collect($updateItems)->pluck('id')->filter()->toArray();
        $items->whereNotIn('model_id', $updateIds)->delete();

        $fields = ['frontend_menu_id', 'model_id'];

        $upsertData = collect($updateItems)->map(function ($updateItem) use ($fields, $relatedModle) {

            $data = ['frontend_menu_id' => $relatedModle->id] + Arr::only($updateItem, $fields);

            if ($modelType = Arr::get($updateItem, 'model_type')) {
                $modelData = $this->resolveModelReference($modelType);
                $data += Arr::only($modelData, ['model_type', 'model_id']);
            }
            return $data;
        })->toArray();

        FrontendMenuItem::upsert($upsertData, ['frontend_menu_id'], $fields);
    }

    private function resolveModelReference($modelType)
    {
        $modelClasses = [];

        $type = $modelType['type'];
        $id = $modelType['id'];

        $related = $modelClasses[$type] ?? null;

        if (!$related) {
            throw new \Exception("Invalid model type: $type");
        }

        $model = $related::findOrFail($id);

        return ['model_type' => $related, 'model_id' => $id];
    }
}
