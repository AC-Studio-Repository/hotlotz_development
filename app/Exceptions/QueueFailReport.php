<?php

namespace App\Exceptions;

use Exception;
use App\Mail\QueueExceptionOccured;
use Illuminate\Support\Facades\Mail;

class QueueFailReport extends Exception
{
}
