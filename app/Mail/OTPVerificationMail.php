<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OTPVerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $otp;

    public function __construct($otp)
    {
        $this->otp = $otp;
    }

    // public function build()
    // {
    //     return $this->subject('Kode OTP Anda')
    //                 ->view('emails.otp');
    // }
    public function build()
    {
        return $this->subject('Kode OTP Reset Password - BloodWellness')
            ->from('projecttiffilkom@gmail.com', 'BloodWellness')
            ->view('emails.otp');
    }
}
