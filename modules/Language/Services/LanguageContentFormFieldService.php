<?php

namespace Modules\Language\Services;

use Modules\Language\Models\LanguageContentFormField;

use App\Abstracts\Services\AbstractModelService;

/**
 * @method LanguageContentFormField getModel()
 * @property LanguageContentFormField $model
 */
class LanguageContentFormFieldService extends AbstractModelService
{
    public function __construct(LanguageContentFormField $languageContentFormField)
    {
        $this->setModel($languageContentFormField);
    }

    public function updateLanguageContentFormFields(array $languageContentFormFields, string $related, $relatedModle)
    {
        $languageContentFormFields = collect($languageContentFormFields)
            ->map(function ($languageContentFormFieldItem) use ($relatedModle) {

                $languageContentFormField = LanguageContentFormField::updateOrCreate(
                    ['id' => $languageContentFormFieldItem['id']] ?? null,
                    [
                        'data_key' => $languageContentFormFieldItem['data_key'] ?? null,
                        'value_type' => $languageContentFormFieldItem['value_type'] ?? null,
                        'component' => $languageContentFormFieldItem['component'] ?? null,
                        'i18n_key' => $languageContentFormFieldItem['i18n_key'] ?? null,
                        'label' => $languageContentFormFieldItem['label'] ?? null,
                        'required' => $languageContentFormFieldItem['required'] ?? false,
                        'disable' => $languageContentFormFieldItem['disable'] ?? false,
                        'readonly' => $languageContentFormFieldItem['readonly'] ?? false,
                        'xs' => $languageContentFormFieldItem['layout']['xs'] ?? null,
                        'sm' => $languageContentFormFieldItem['layout']['sm'] ?? null,
                        'md' => $languageContentFormFieldItem['layout']['md'] ?? null,
                        'lg' => $languageContentFormFieldItem['layout']['lg'] ?? null,
                        'xl' => $languageContentFormFieldItem['layout']['xl'] ?? null,
                        'placeholder' => $languageContentFormFieldItem['placeholder'] ?? null,
                        'language_content_form_id' => $relatedModle->id,
                    ]
                );

                $languageContentFormField = LanguageContentFormField::findOrFail($languageContentFormField->id);

                return $languageContentFormField;
            });
        return $languageContentFormFields->toArray();
    }
}
