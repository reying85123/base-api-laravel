<?php

namespace Modules\Language\Services;

use Modules\Language\Models\LanguageSetting;

use App\Abstracts\Services\AbstractModelService;

/**
 * @method LanguageSetting getModel()
 * @property LanguageSetting $model
 */
class LanguageSettingService extends AbstractModelService
{
    public function __construct(LanguageSetting $languageSetting)
    {
        $this->setModel($languageSetting);
    }
}
