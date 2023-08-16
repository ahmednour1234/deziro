<?php

namespace App\Mail;



use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use App\Models\User;


class PasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;

    public $code;

    public function __construct($code)
    {
        $this->code = $code;
    }

    public function build()
    {

        return $this->from('info@deziro.syscomdemos.com', 'SyscomDemos')
        ->view('admin.emails.password_reset')
            ->subject('Password Reset Code')
            ->with(['code' => $this->code]);
    }
}


