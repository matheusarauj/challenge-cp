<?php


namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class DuplicateMailException extends Exception
{
    protected $message = "Este e-email já está cadastrado no sistema.";
    protected $code = Response::HTTP_CONFLICT;
}
