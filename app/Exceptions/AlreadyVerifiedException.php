<?php


namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class AlreadyVerifiedException extends Exception
{
    protected $message = "Este e-mail já está verificado.";
    protected $code = Response::HTTP_BAD_REQUEST;
}
