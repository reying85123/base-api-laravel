<?php

namespace App\Listeners\Base;

use Modules\MailLog\Services\MailLogService;

use App\Enums\MailLogStateEnum;

use Illuminate\Mail\Events\MessageSent;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class MailLogSentMessageListener
{
    protected $mailLogService;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(MailLogService $mailLogService)
    {
        $this->mailLogService = $mailLogService;
    }

    /**
     * Handle the event.
     *
     * @param  \Illuminate\Mail\Events\MessageSent  $event
     * @param  MailLogService $mailLogService
     * @return void
     */
    public function handle(MessageSent $event)
    {
        $this->mailLogService
            ->fill([
                'from' => $this->getFrom($event),
                'to' => $this->getTo($event),
                'cc' => $this->getCc($event),
                'bcc' => $this->getBcc($event),
                'reply_to' => $this->getReplyTo($event),
                'subject' => $this->getSubject($event),
                'send_datetime' => $this->getSendDatetime($event),
                'content' => $this->getContent($event),
            ])
            ->setState(MailLogStateEnum::SUCCESS)
            ->save();
    }

    protected function getFrom(MessageSent $event)
    {
        $fromArray = array_keys($event->message->getFrom());

        return join(',', $fromArray);
    }

    protected function getTo(MessageSent $event)
    {
        $toArray = array_keys($event->message->getTo());

        return join(',', $toArray);
    }

    protected function getCc(MessageSent $event)
    {
        if (empty($event->message->getCc())) {
            return null;
        }

        $ccArray = array_keys($event->message->getCc() ?: []);

        return join(',', $ccArray);
    }

    protected function getBcc(MessageSent $event)
    {
        if (empty($event->message->getBcc())) {
            return null;
        }

        $bccArray = array_keys($event->message->getBcc());

        return join(',', $bccArray);
    }

    protected function getReplyTo(MessageSent $event)
    {
        return $event->message->getReplyTo();
    }

    protected function getSubject(MessageSent $event)
    {
        return $event->message->getSubject();
    }

    protected function getSendDatetime(MessageSent $event)
    {
        return $event->message->getDate();
    }

    protected function getContent(MessageSent $event)
    {
        return $event->message->getBody();
    }
}
