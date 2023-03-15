<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Mail;

class ItemQueueFailReport extends Exception
{
    /**
     * Report or log an exception.
     *
     * @return void
     */
    public function report(Exception $exception)
    {
        // $exceptionHtml = $this->render(null, $exception)->getContent();

        // Mail::to('maychothet@nexlabs.co')->cc(['heinwintoe@nexlabs.co'])
        // ->send(new \App\Mail\QueueExceptionOccured($exceptionHtml));

        // parent::report($exception);
    }
}
