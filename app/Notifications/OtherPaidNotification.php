<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OtherPaidNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public $order;
    public function __construct($order)
    {
        $this->order = $order;
    }


    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message'=>'Your order paid by other person',
            'order'=>$this->order['service_name'].' '.'from'.' '.$this->order['salon_name'],
            'created_at' => now(),
        ];
    }
}
