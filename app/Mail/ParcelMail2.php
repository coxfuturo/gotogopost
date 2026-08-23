<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

use Attachment;

class ParcelMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */

    public $pdfPath;
    public $parcel;
    public function __construct(array $data)
    {
        $this->pdfPath = $data[0];
        $this->parcel = $data[1];
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Gotogo Post Parcel',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'franchise.mail.mailview',
            with: [
                'parcel' => $this->parcel,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {

        $fileSystemPath = $this->pdfPath;
        return [
            \Illuminate\Mail\Mailables\Attachment::fromPath($fileSystemPath)
                ->as('parcel.pdf')
                ->withMime('application/pdf'), // Note the lowercase 'application'
        ];
    }
}
