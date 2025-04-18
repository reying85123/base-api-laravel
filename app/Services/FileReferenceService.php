<?php

namespace App\Services;


class FileReferenceService
{
    /**
     * 更新或建立 FileReference，並且與關聯的目標模型同步。
     *
     * @param array $data
     * @param string $related
     * @param string $foreign
     * @param string $relationName 關聯名稱 (如 'image', 'file' 等)
     * @return void
     */
    public static function updateOrCreateFileReference(array $data = [], string $related, $foreign, string $relationName = null)
    {
        $fileReference = isset($data['id']) ? $related::findOrFail($data['id']) : new $related();
        $fileReference->fill($data);
        $fileReference->fill(['tag' => $relationName]);
        $fileReference->model()->associate($foreign);
        $fileReference->save();

        $relatedId = (isset($data['file_info']) && isset($data['file_info']['id'])) ? [$data['file_info']['id']] : [];
        $fileReference->{$relationName}()->sync($relatedId);
    }
}
