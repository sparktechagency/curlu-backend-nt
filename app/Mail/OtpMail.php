<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $otp, $email_type;
    public function __construct($otp, $email_type = "reset_password")
    {
        $this->otp = $otp;
        $this->email_type = $email_type;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = match ($this->email_type) {
            'reset_password' => 'Password Reset OTP',
            'register' => 'Account Verification OTP',
        };

        return new Envelope(
            subject: $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'otp_template',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
