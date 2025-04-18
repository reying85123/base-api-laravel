<?php

namespace App\Listeners\Base;

use Modules\System\Models\SystemLog;

use App\Events\Base\ModelRecordSystemLogEvent;

use App\Enums\ModelLogTypeEnum;

use App\Contracts\Models\RecordSystemLogInterface;
use App\Contracts\Models\RecordSystemLogMessageInterface;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SaveModelRecordSystemLogListener
{

    protected $messageAttribute;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        $this->messageAttribute = [];
    }

    /**
     * Handle the event.
     *
     * @param  ModelRecordSystemLogEvent  $event
     * @return void
     */
    public function handle(ModelRecordSystemLogEvent $event)
    {
        if (!$event->user) {
            return false;
        }

        $modelLogType = ModelLogTypeEnum::toSelectArray();

        $actionModel = $event->model;

        $this->messageAttribute = [
            '#item#' => $modelLogType[$event->logType],
            '#itemKeyName#' => $actionModel->getKeyName(),
            '#itemKey#' => $actionModel->getKey(),
        ];

        $messageTemplates = $this->messages();

        if ($actionModel instanceof RecordSystemLogInterface) {
            $this->messageAttribute = [
                '#item#' => $actionModel->getLogName() ?: $modelLogType[$event->logType],
                '#itemKeyName#' => $actionModel->getLogKeyName() ?: $actionModel->getKeyName(),
                '#itemKey#' => $actionModel->getLogKeyValue() ?: $actionModel->getKey(),
            ];
        }

        if ($actionModel instanceof RecordSystemLogMessageInterface) {
            if (!empty($addMessageAttributes = $actionModel->addMessageAttributes())) {
                $this->messageAttribute = array_merge($this->messageAttribute, $addMessageAttributes);
            }

            $messageTemplates = array_merge($messageTemplates, $actionModel->getLogMessages());
        }

        $message = $this->getMessage($messageTemplates[$event->action] ?? '');

        $systemLog = new SystemLog([
            'title' => $modelLogType[$event->logType],
            'description' => $message,
            'type' => $event->logType,
            'sourceip' => request()->getClientIp(),
        ]);

        $systemLog->user()->associate($event->user);

        $systemLog->save();
    }

    protected function messages()
    {
        return [
            'create' => '建立#item#(#itemKeyName#:#itemKey#)',
            'update' => '更新#item#(#itemKeyName#:#itemKey#)',
            'delete' => '刪除#item#(#itemKeyName#:#itemKey#)',
        ];
    }

    protected function getMessage($message)
    {
        return str_replace(array_keys($this->messageAttribute), array_values($this->messageAttribute), $message ?: '');
    }
}
