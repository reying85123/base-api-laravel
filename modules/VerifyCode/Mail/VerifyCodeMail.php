<?php

namespace Modules\VerifyCode\Mail;

use Modules\VerifyCode\Models\VerifyCode;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifyCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $validationCode;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(VerifyCode $verifyCode)
    {
        $this->validationCode = $verifyCode->token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('verifyCode.resources.emails.verifyCode')->subject('驗證碼');
    }
}
