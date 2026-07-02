<?php

namespace App\Notifications;

use App\Models\Complaint;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ComplaintReceived extends Notification
{
    use Queueable;

    public function __construct(public Complaint $complaint) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('complaints.email_received_subject', ['id' => $this->complaint->id]))
            ->line(__('complaints.email_received_body', [
                'name' => $this->complaint->name,
                'subject' => $this->complaint->subject,
            ]))
            ->action(__('complaints.view_complaint'), url('/admin/complaints/' . $this->complaint->id));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'complaint_id' => $this->complaint->id,
            'name' => $this->complaint->name,
            'subject' => $this->complaint->subject,
        ];
    }
}
