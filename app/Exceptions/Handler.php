<?php

namespace App\Exceptions;

use Exception;
use App\Exceptions\QueueFailReport;
use App\Mail\QueueExceptionOccured;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\Debug\ExceptionHandler as SymfonyExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        if ($exception instanceof QueueFailReport) {
            $this->sendEmail($exception);
        }

        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        return parent::render($request, $exception);
    }

    /**
     * Send email an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function sendEmail(Exception $exception)
    {
        try {
            // $e = FlattenException::create($exception);

            // $handler = new SymfonyExceptionHandler();

            // $html = $handler->getHtml($e);

            // Mail::to('heinwintoe@nexlabs.co')->cc(['htaylatwin@nexlabs.co','maychothet@nexlabs.co'])->send(new QueueExceptionOccured($html));
        } catch (Exception $ex) {
        }
    }
}
