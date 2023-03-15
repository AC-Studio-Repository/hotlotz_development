<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class QueueExceptionOccured extends Mailable
{
    use Queueable, SerializesModels;

    protected $exceptionHtml;

    /**
     * Create a new message instance.
     *
     * @param $exceptionHtml
     * @internal param Exception $e
     */
    public function __construct($exceptionHtml)
    {
        $this->exceptionHtml = $exceptionHtml;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Exception occured ! (On Queue)')
            ->view('emails.exception')
            ->with('exceptionHtml', $this->exceptionHtml);
    }
}
