<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailUpdate extends Mailable
{
    use Queueable, SerializesModels;
    public $user;
    public $new_mail_id;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $new_mail_id)
    {
        $this->user = $user;
        $this->new_mail_id = $new_mail_id;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.update')->with([
            'user' => $this->user,
            'newmail' => $this->new_mail_id,
        ]);
    }
}
