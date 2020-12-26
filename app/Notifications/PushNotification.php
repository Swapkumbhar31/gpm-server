<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Benwilkins\FCM\FcmMessage;
class PushNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function via($notifiable)
    {
        return ['fcm'];
    }

    public function toFcm($notifiable)
    {
        $message = new FcmMessage();
        $message->content([
              'title'        => 'Foo',
              'body'         => 'Bar',
              'sound'        => '', // Optional
              'icon'         => '', // Optional
              'click_action' => '' // Optional
          ])->data([
              'param1' => 'baz' // Optional
          ])->priority(FcmMessage::PRIORITY_HIGH); // Optional - Default is 'normal'.
          return $message;
    }
}
