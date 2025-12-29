<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\OrderCancellationRequest;

class NewCancellationRequest extends Notification
{
    use Queueable;

    protected $requestModel;

    public function __construct(OrderCancellationRequest $requestModel)
    {
        $this->requestModel = $requestModel;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $order = $this->requestModel->order;

        return (new MailMessage)
                    ->subject('New order cancellation request')
                    ->line("Order #{$order->id} has a new cancellation request.")
                    ->line('Reason: ' . ($this->requestModel->reason ?? 'No reason provided'))
                    ->action('View Request', url('/admin/cancellation-requests/' . $this->requestModel->id))
                    ->line('Please review the request in the admin panel.');
    }
}
