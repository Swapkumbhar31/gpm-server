<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewMasterMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $password)
    {
        $this->user = $user;
        $this->password = $password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.newmaster')
            ->subject('Congratulations!! You are a master Franchise now.')
            ->with([
                'username' => $this->user->email,
                'name' => $this->user->name,
                // 'password' => Crypt::decryptString($this->user->password),
                'password' => $this->password,
            ]);
    }
}
