<?php

namespace Modules\Relationship\Services\Relationship;

use Modules\Relationship\Models\Relationship;

use App\Abstracts\Services\AbstractModelService;

use Illuminate\Http\Request;

/**
 * @method Relationship getModel()
 * @property \Modules\Relationship\Models\Relationship $model
 */
class RelationshipService extends AbstractModelService
{
    protected $modelMapping = [];

    public function __construct(Relationship $model)
    {
        $this->setModel($model);
    }

    public function associateEntity($entity)
    {
        $model = $this->resolvePolymorphicRelation($entity);
        return $this->model->entity()->associate($model);
    }

    public function associateRelatedEntity($relatedEntity)
    {
        $model = $this->resolvePolymorphicRelation($relatedEntity);
        return $this->model->relatedEntity()->associate($model);
    }

    public function filterQuery(Request $request)
    {
        $relationship = Relationship::query();

        if ($keyword = $request->input('keyword')) {
        }

        if ($entityType = $request->input('entity_type')) {
            $entityModel = $this->getModelClass($entityType);
            $entityId = $request->input('entity_id');
            if ($entityModel) {
                $relationship->whereHasMorph('entity', [$entityModel], function ($q) use ($entityId) {
                    if ($entityId) {
                        $q->whereIn('id', explode(',', $entityId));
                    }
                });
            }
        }

        if ($relatedEntityType = $request->input('related_entity_type')) {
            $relatedEntityModel = $this->getModelClass($relatedEntityType);
            $relatedEntityId = $request->input('related_entity_id');
            if ($relatedEntityModel) {
                $relationship->whereHasMorph('relatedEntity', [$relatedEntityModel], function ($q) use ($relatedEntityId) {
                    if ($relatedEntityId) {
                        $q->whereIn('id', explode(',', $relatedEntityId));
                    }
                });
            }
        }

        if ($group = $request->input('group')) {
            $relationship->whereGroup($group);
        }

        if ($orderby = $request->input('orderby')) {
            $relationship->queryOrderBy($orderby);
        }
        return $relationship;
    }

    private function resolvePolymorphicRelation($entity)
    {
        $type = $entity['type'];
        $id = $entity['id'];

        $related = $this->getModelClass($type);

        if (!$related) {
            throw new \Exception("Invalid model type: $type");
        }

        return $id ? $related::findOrFail($id) : null;
    }

    private function getModelClass($type)
    {
        return $this->modelMapping[$type] ?? null;
    }
}
