<?php

namespace Modules\Relationship\Models\Traits;

use Modules\Relationship\Models\Relationship;

trait HasRelationshipTrait
{

    protected $relatedEntities;

    /**
     * Get the entity entities for the model
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function entity()
    {
        return $this->morphMany(Relationship::class, 'entity', 'entity_type', 'entity_id');
    }

    /**
     * Get the relatedEntity entities for the model
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function relatedEntity()
    {
        return $this->morphMany(Relationship::class, 'relatedEntity', 'related_entity_type', 'related_entity_id');
    }

    /**
     * Get the related entities based on the relationship type.
     *
     * @param string|null $group
     */
    public function getRelatedEntities($group = null)
    {
        if ($this->entity === null) {
            return collect([]);
        }

        if ($this->relatedEntities !== null) {
            return $this->relatedEntities;
        }

        $entity = $this->entity;
        if ($group) {
            $entity = $entity->where('group', $group);
        }

        return $this->relatedEntities = $entity
            ->map(function ($relationship) {
                return $relationship->relatedEntity;
            })
            ->values()
            ->map
            ->only(['id']);
    }
}
