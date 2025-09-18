<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GenericMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subjectLine;
    public $bodyMessage;
    public $buttonUrl;
    public $buttonText;

    /**
     * Create a new message instance.
     */
    public function __construct($subjectLine, $bodyMessage, $buttonUrl = null, $buttonText = null)
    {
        $this->subjectLine = $subjectLine;
        $this->bodyMessage = $bodyMessage;
        $this->buttonUrl   = $buttonUrl;
        $this->buttonText  = $buttonText;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject($this->subjectLine)
                    ->view('emails.generic')   // <-- use a Blade view
                    ->with([
                        'subjectText' => $this->subjectLine,
                        'body'        => $this->bodyMessage,
                        'buttonUrl'   => $this->buttonUrl,
                        'buttonText'  => $this->buttonText,
                    ]);
    }
}
