<?php


namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class ExperienceNotFoundException extends Exception
{
    protected $message = "Nenhuma experiência encontrada para este ID";
    protected $code = Response::HTTP_NOT_FOUND;
}
