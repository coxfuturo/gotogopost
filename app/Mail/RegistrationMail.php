<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

// use Attachment;

class RegistrationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */

    public $username;
    public $password;
    public $route;
    public function __construct(array $data)
    {
        $this->username = $data['username'];
        $this->password = $data['password'];
        $this->route = $data['route'];
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Gotogo Post Registration Details',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'franchise.mail.registration',
            with: [
                'username' => $this->username,
                'password' => $this->password,
                'route' => $this->route,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    // public function attachments(): array
    // {

    //     $fileSystemPath = $this->pdfPath;
    //     return [
    //         \Illuminate\Mail\Mailables\Attachment::fromPath($fileSystemPath)
    //             ->as('parcel.pdf')
    //             ->withMime('application/pdf'), // Note the lowercase 'application'
    //     ];
    // }
}
