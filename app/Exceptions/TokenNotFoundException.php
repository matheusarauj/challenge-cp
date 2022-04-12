<?php


namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class TokenNotFoundException extends Exception
{
    protected $message = "Token não encontrado.";
    protected $code = Response::HTTP_UNAUTHORIZED;
}
