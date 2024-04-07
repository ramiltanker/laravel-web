<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;
use App\Models\Path;
use App\Models\Comment;

class StatMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $pathCount;
    protected $commentCount;

    /**
     * Create a new message instance.
     */
    public function __construct(int $pathCount, int $commentCount)
    {
        $this->pathCount = $pathCount;
        $this->commentCount = $commentCount;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('ramil-frontend@mail.ru'),
            subject: 'Statistic Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.statistic',
            with: [
                'pathCount' => $this->pathCount,
                'commentCount' => $this->commentCount,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}