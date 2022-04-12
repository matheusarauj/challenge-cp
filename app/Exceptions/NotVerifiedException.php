<?php


namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class NotVerifiedException extends Exception
{
    protected $message = "E-mail não verificado.";
    protected $code = Response::HTTP_NOT_ACCEPTABLE;
}
