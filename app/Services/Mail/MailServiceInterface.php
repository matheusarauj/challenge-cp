<?php


namespace App\Services\Mail;


interface MailServiceInterface
{
    public function sendConfirmationCode(int $code, string $email, string $name) : void;

    public function verifyRecordMail(string $email) : bool;
}
