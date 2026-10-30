<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CronFailedMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $commandName;
    public string $errorMessage;
    public string $failedAt;

    /**
     * Create a new message instance.
     */
    public function __construct(string $commandName, string $errorMessage = 'Unknown error or non-zero exit code.')
    {
        $this->commandName = $commandName;
        $this->errorMessage = $errorMessage;
        $this->failedAt = now('Asia/Kolkata')->format('d M Y, H:i:s T');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "[CRITICAL ALERT] DigitalBhudev Cron Job Failed: {$this->commandName}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.cron-failed',
        );
    }
}
