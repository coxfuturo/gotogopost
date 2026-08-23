<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

class MailToFranchise extends Mailable
{
    use Queueable, SerializesModels;

    public $attachment; // Array to hold multiple attachments
    public $subject; // Email subject
    public $emailbody; // Email body content
    public $fromFranchiseDetails; // From Franchise details
    public $toPhone; // Recipient phone number
    public $toEmail; // Recipient email number
    public $toAddress; // Recipient address

    public function __construct(array $data)
    {
        // Expect attachments as an array
        $this->attachment = $data['attachments'] ?? [];
        $this->subject = $data['subject'] ?? 'No Subject';
        $this->emailbody = $data['emailbody'] ?? '';

        // Collect the additional data fields
        $this->fromFranchiseDetails = $data['fromFranchiseDetails'] ?? null;
        $this->toPhone = $data['toPhone'] ?? null;
        $this->toAddress = $data['toAddress'] ?? null;
        $this->toEmail = $data['toEmail'] ?? null;
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
            view: 'franchise.mail.mailtofranchiseview',
            with: [
                'emailbody' => $this->emailbody,
                'subject' => $this->subject,
                'fromFranchiseDetails' => $this->fromFranchiseDetails,
                'toPhone' => $this->toPhone,
                'toEmail' => $this->toEmail,
                'toAddress' => $this->toAddress,
                'attachments' => $this->attachment,
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
