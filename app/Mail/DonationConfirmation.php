<?php

namespace App\Mail;

use App\Models\Donation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DonationConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public Donation $donation;
    public string $type;

    protected string $appName;

    /** @param string $type Type of notification: instant, under_review, completed, or failed. */
    public function __construct(Donation $donation, string $type = 'completed')
    {
        $this->donation = $donation;
        $this->type = $type;
        $this->appName = config('app.name', 'Sahem');
    }

    /** Set the email subject based on the notification type. */
    public function envelope(): Envelope
    {
        $subjects = [
            'instant' => __('emails.donation_subject_instant', ['name' => $this->appName]),
            'under_review' => __('emails.donation_subject_under_review', ['name' => $this->appName]),
            'completed' => __('emails.donation_subject_completed', ['name' => $this->appName]),
            'failed' => __('emails.donation_subject_failed', ['name' => $this->appName]),
        ];

        return new Envelope(
            subject: $subjects[$this->type] ?? __('emails.donation_subject_default', ['name' => $this->appName]),
        );
    }

    /** Render the donation email view. */
    public function content(): Content
    {
        return new Content(
            view: 'emails.donation',
        );
    }

    /** Attach the PDF receipt if it exists and is readable. */
    public function attachments(): array
    {
        $pdfPath = storage_path('app/receipts/receipt-' . $this->donation->id . '.pdf');
        if (file_exists($pdfPath) && is_readable($pdfPath)) {
            return [$pdfPath];
        }
        return [];
    }
}
