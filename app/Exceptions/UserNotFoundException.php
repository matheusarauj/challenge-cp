<?php


namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class UserNotFoundException extends Exception
{
    protected $message = "Usuário não encontrado";
    protected $code = Response::HTTP_NOT_FOUND;
}
