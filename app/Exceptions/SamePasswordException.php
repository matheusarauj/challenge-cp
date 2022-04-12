<?php


namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class SamePasswordException extends Exception
{
    protected $message = "Mesma senha cadastrada novamente.";
    protected $code = Response::HTTP_CONFLICT;
}
