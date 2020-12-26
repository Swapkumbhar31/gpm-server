<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactUs extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($request = null)
    {
        $this->request = $request;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.contactus')
            ->subject('Contact Us')
            ->with([
                'request' => $this->request,
                // 'name' => $this->request->name,
                // 'email' => $this->request->email,
                // 'message' => $this->request->message,
            ]);
    }
}
