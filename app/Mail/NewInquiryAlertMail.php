<?php

namespace App\Mail;

use App\Models\Inquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewInquiryAlertMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Inquiry $inquiry)
    {
    }

    public function envelope(): Envelope
    {
        $subject = $this->inquiry->type === 'booking'
            ? '🕉️ New Pooja Booking Request: ' . ($this->inquiry->pooja_service ?: 'Vedic Ritual') . ' - ' . $this->inquiry->name
            : '📩 New DigitalBhudev Inquiry: ' . ($this->inquiry->subject ?: 'General Inquiry') . ' - ' . $this->inquiry->name;

        return new Envelope(
            subject: $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.new-inquiry-alert',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
