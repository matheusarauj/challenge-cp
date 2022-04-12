<?php


namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class WrongPasswordException extends Exception
{
    protected $message = "Senha incorreta";
    protected $code = Response::HTTP_UNAUTHORIZED;
}
