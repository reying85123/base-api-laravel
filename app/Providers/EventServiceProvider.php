<?php

namespace App\Providers;

use App\Events\Base\ModelRecordSystemLogEvent;
use App\Listeners\Base\SaveModelRecordSystemLogListener;

use Illuminate\Mail\Events\MessageSent;
use App\Listeners\Base\MailLogSentMessageListener;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        ModelRecordSystemLogEvent::class => [
            SaveModelRecordSystemLogListener::class,
        ],
        MessageSent::class => [
            MailLogSentMessageListener::class,
        ],
        \SocialiteProviders\Manager\SocialiteWasCalled::class => [
            \SocialiteProviders\Line\LineExtendSocialite::class . '@handle',
            \SocialiteProviders\Google\GoogleExtendSocialite::class . '@handle',
            \SocialiteProviders\Facebook\FacebookExtendSocialite::class . '@handle',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
