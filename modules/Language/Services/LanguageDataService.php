<?php

namespace Modules\Language\Services;

use Modules\Language\Models\LanguageData;

use App\Abstracts\Services\AbstractModelService;

class LanguageDataService extends AbstractModelService
{

    public function __construct(LanguageData $languageData)
    {
        $this->setModel($languageData);
    }

    public function updateLanguageDatas(array $languageDatas)
    {
        $languageDatas = collect($languageDatas)
            ->map(function ($languageDataItem) {

                $languageData = LanguageData::updateOrCreate(
                    ['id' => $languageDataItem['id']],
                    [
                        'data_key' => $languageDataItem['data_key'],
                        'value_type' => $languageDataItem['value_type'],
                        'usage_type' => $languageDataItem['usage_type'],
                        'component' => $languageDataItem['component'],
                        'i18n_key' => $languageDataItem['i18n_key'],
                        'label' => $languageDataItem['label'],
                        'required' => $languageDataItem['required'],
                        'disable' => $languageDataItem['disable'],
                        'readonly' => $languageDataItem['readonly'],
                        'xs' => $languageDataItem['layout']['xs'],
                        'sm' => $languageDataItem['layout']['sm'],
                        'md' => $languageDataItem['layout']['md'],
                        'lg' => $languageDataItem['layout']['lg'],
                        'xl' => $languageDataItem['layout']['xl'],
                        'placeholder' => $languageDataItem['placeholder'],
                        'value' => $languageDataItem['value'],
                        'locale' => $languageDataItem['locale'],
                    ]
                );

                $languageData = LanguageData::findOrFail($languageData->id);

                return $languageData;
            });
        return $languageDatas->toArray();
    }
}
