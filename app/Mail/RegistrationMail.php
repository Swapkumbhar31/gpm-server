<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Crypt;
class RegistrationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.registration')
            ->subject('Welcome to GPM community')
            ->with([
                'username' => $this->user->email,
                // 'password' => Crypt::decryptString($this->user->password),
                'password' => $this->user->mailpassword,
            ]);
    }
}
