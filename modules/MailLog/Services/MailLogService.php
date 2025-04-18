<?php

namespace Modules\MailLog\Services;

use Modules\MailLog\Models\MailLog;

use App\Abstracts\Services\AbstractModelService;
use App\Services\Traits\HasModel;

/**
 * @property MailLog $model
 * @method MailLog getModel()
 */
class MailLogService extends AbstractModelService
{
    use HasModel;

    public function __construct(MailLog $model)
    {
        $this->model = $model;
    }

    public function setState($state)
    {
        switch (true) {
            case $state === $this->model->state:
                break;
        }

        $this->model->state = $state;

        return $this;
    }
}