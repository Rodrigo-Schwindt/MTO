<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

class NewsletterMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $subjectText,
        public string $bodyText,
        public array $attachmentFiles = []
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: $this->subjectText);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.newsletter');
    }

    public function attachments(): array
    {
        return array_map(
            fn($a) => Attachment::fromPath($a['path'])
                ->as($a['name'])
                ->withMime($a['mime']),
            $this->attachmentFiles
        );
    }
}
