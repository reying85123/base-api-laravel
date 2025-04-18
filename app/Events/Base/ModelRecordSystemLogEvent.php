<?php

namespace App\Events\Base;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

use Modules\UserAuth\Services\UserAuthService;

use App\Enums\ModelLogTypeEnum;

use Illuminate\Database\Eloquent\Model;

class ModelRecordSystemLogEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $model;
    public $action;
    public $logType;

    public $user;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Model $model, $action, $logType)
    {
        if (!ModelLogTypeEnum::hasValue($logType)) {
            throw new \Exception('未註冊的模型紀錄類別');
        }

        $this->model = $model;
        $this->action = $action;
        $this->logType = $logType;

        $this->user = UserAuthService::toUser();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
