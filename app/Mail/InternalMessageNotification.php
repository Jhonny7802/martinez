<?php

namespace App\Mail;

use App\Models\InternalMessage;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InternalMessageNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $message;
    public $recipient;

    public function __construct(InternalMessage $message, User $recipient)
    {
        $this->message = $message;
        $this->recipient = $recipient;
    }

    public function build()
    {
        return $this->subject('Nuevo Mensaje Interno - ' . $this->message->subject)
                    ->view('emails.internal_message_notification')
                    ->with([
                        'message' => $this->message,
                        'recipient' => $this->recipient,
                        'sender' => $this->message->sender
                    ]);
    }
}
