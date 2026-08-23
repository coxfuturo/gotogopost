<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

class MailToMail extends Mailable
{
    use Queueable, SerializesModels;

    public $attachment; // Array to hold multiple attachments
    public $subject; // Email subject
    public $emailbody; // Email body content
    public $donwloadLink; // Email body content

    public function __construct(array $data, array $viewData = [])
    {
        $this->attachment = $data['attachments'] ?? [];
        $this->subject = $data['subject'] ?? 'No Subject';
        $this->emailbody = $data['emailbody'] ?? '';
        $this->donwloadLink = $data['donwloadLink'] ?? '';

        \Log::info('MailToMail initialized', [
            'subject' => $this->subject,
            'attachments' => $this->attachment
        ]);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subject,
        );
    }


    public function content(): Content
    {
        return new Content(
            view: 'franchise.mail.mailtomailview',
            with: [
                'emailbody' => $this->emailbody,
                'donwloadLink' => $this->donwloadLink,
            ]
        );
    }


    public function attachments(): array
    {
        $attachments = [];
        foreach ($this->attachment as $filePath) {
            $attachments[] = Attachment::fromPath($filePath)
                ->as(basename($filePath)) // Use the original file name
                ->withMime(mime_content_type($filePath)); // Automatically get MIME type
        }
        return $attachments;
    }
}
