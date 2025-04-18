<?php

namespace Modules\Mailinfo\Services;

use Modules\Mailinfo\Models\Mailinfo;

use App\Abstracts\Services\AbstractModelService;

use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;

/**
 * @method Mailinfo getModel()
 * @property Mailinfo $model
 */
class MailinfoService extends AbstractModelService
{
    public function __construct(Mailinfo $mailinfo)
    {
        $this->setModel($mailinfo);
    }

    /**
     * 發送樣板信件
     *
     * @param Mailinfo $mailinfo
     * @param string|array|null $to
     */
    public static function sendMail(Mailinfo $mailinfo, $to = null, $options = [])
    {
        Mail::send([], [], function (Message $message) use ($mailinfo, $to, $options) {
            if ($mailinfo->fromname !== null) {
                $message->from(config('mail.from.address', $mailinfo->fromname));
            }

            if ($mailinfo->repeatname !== null) {
                $message->replyTo($mailinfo->repeatmail, $mailinfo->repeatname);
            }

            if ($mailinfo->cc !== null) {
                $message->cc(explode(',', $mailinfo->cc));
            }

            if ($mailinfo->bcc !== null) {
                $message->bcc(explode(',', $mailinfo->bcc));
            }

            if (isset($options['attach'])) {
                $attaches = is_scalar($options['attach']) ? $options['attach'] : [$options['attach']];

                foreach ($attaches as $attach) {
                    if (isset($attach['file'])) {
                        $message->attach($attach['file'], $attach['options'] ?? []);
                    } else if (isset($attach['data'])) {
                        $message->attachData($attach['data'], $attach['name'] ?? '', $attach['options'] ?? []);
                    }
                }
            }
            $message
                ->subject($mailinfo->subject)
                ->to($to ? $to : $mailinfo->tomail)
                ->setBody(isset($options['htmlContent']) ? $options['htmlContent'] : $mailinfo->content, 'text/html');
        });
    }

    /**
     * 替換樣板信件文本
     *
     * @param Mailinfo $mailinfo
     * @param array $displaceOptions
     */
    public static function displaceMailContent(Mailinfo $mailinfo, $displaceOptions)
    {
    }
}
