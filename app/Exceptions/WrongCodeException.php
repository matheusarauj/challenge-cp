<?php


namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class WrongCodeException extends Exception
{
    protected $message = "Código incorreto.";
    protected $code = Response::HTTP_BAD_REQUEST;
}
