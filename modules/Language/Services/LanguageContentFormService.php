<?php

namespace Modules\Language\Services;

use Modules\Language\Models\LanguageContentForm;

use Modules\Language\Services\LanguageContentFormFieldService;

use App\Abstracts\Services\AbstractModelService;

/**
 * @method LanguageContentForm getModel()
 * @property LanguageContentForm $model
 */
class LanguageContentFormService extends AbstractModelService
{

    protected $languageContentFormFieldService;

    public function __construct(LanguageContentForm $languageContentForm)
    {
        $this->setModel($languageContentForm);
    }

    public function updateLanguageContentFormFields(array $updateLanguageContentFormFields)
    {
        $this->getLanguageContentFormFieldService()->updateLanguageContentFormFields($updateLanguageContentFormFields, LanguageContentForm::class, $this->model);
    }

    private function getLanguageContentFormFieldService()
    {
        if (!$this->languageContentFormFieldService) {
            $this->languageContentFormFieldService = app(LanguageContentFormFieldService::class);
        }
        return $this->languageContentFormFieldService;
    }
}
