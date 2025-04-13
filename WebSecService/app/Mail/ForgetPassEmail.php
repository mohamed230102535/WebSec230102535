<?php


namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ForgetPassEmail extends Mailable
{
    use Queueable, SerializesModels;

    private $link;
    private $name;

    public function __construct($link, $name)
    {
        $this->link = $link;
        $this->name = $name;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reset Your Password'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.ResetForm',
            with: [
                'link' => $this->link,
                'name' => $this->name
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
