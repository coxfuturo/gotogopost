<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

class E2HDeliveredMail extends Mailable
{
    use Queueable, SerializesModels;

    public $attachment;
    public $subject;
    public $emailbody;
    public $recipient_name;
    public $recipient_phone;
    public $recipient_address;


    public function __construct(array $data)
    {
        // Expect attachments as an array
        $this->attachment = $data['attachments'] ?? [];
        $this->subject = $data['subject'] ?? 'No Subject';
        $this->emailbody = $data['emailbody'] ?? '';

        $this->recipient_name = $data['recipient_name'] ?? null;
        $this->recipient_phone = $data['recipient_phone'] ?? null;
        $this->recipient_address = $data['recipient_address'] ?? null;
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
            view: 'franchise.mail.E2hDeliveredMailview',
            with: [
                'emailbody' => $this->emailbody,
                'subject' => $this->subject,
                'attachments' => $this->attachment,
                'recipient_name' => $this->recipient_name,
                'recipient_phone' => $this->recipient_phone,
                'recipient_address' => $this->recipient_address,
            ]
        );
    }


    public function attachments(): array
    {
        $attachments = [];
        foreach ($this->attachment as $attachmentData) {
            $path = $attachmentData['file_path'];
            $name = $attachmentData['file_name'];

            $attachments[] = Attachment::fromPath($path)
                ->as($name)
                ->withMime(mime_content_type($path));
        }
        return $attachments;
    }
}
