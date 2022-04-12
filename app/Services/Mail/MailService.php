<?php


namespace App\Services\Mail;


use App\Models\User;
use Illuminate\Support\Facades\Mail;

class MailService implements MailServiceInterface
{
    /**
     * @param $code
     * @param $email
     * @param $name
     */
    public function sendConfirmationCode(int $code, string $email, string $name) : void
    {
        Mail::send(
            'emails.code',
            array('code' => $code),
            function ($message) use ($email, $name) {
                $message->to($email, $name)
                    ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
                    ->subject('Capyba Confirmation Code');
            }
        );
    }

    public function verifyRecordMail(string $email) : bool
    {
        return User::where('email', $email)->first() !== null;
    }
}
