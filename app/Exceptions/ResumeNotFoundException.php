<?php


namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class ResumeNotFoundException extends Exception
{
    protected $message = "Nenhum currículo encontrado para este ID";
    protected $code = Response::HTTP_NOT_FOUND;
}
