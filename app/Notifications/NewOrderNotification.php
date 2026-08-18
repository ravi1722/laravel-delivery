<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewOrderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        private readonly Order $order
    ) {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("New Order Received — #{$this->order->order_number}")
            ->greeting("Hello {$notifiable->name}!")
            ->line("You have received a new order!")
            ->line("**Order Number:** #{$this->order->order_number}")
            ->line("**Customer:** {$this->order->user->name}")
            ->line("**Total Amount:** ₹" . number_format($this->order->total_amount, 2))
            ->line("**Payment Method:** " . ucfirst($this->order->payment_method))
            ->action('View Order', route('restaurant.dashboard'))
            ->line('Please confirm the order as soon as possible.');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type'         => 'new_order',
            'title'        => 'New Order Received!',
            'message'      => "Order #{$this->order->order_number} — ₹" . number_format($this->order->total_amount, 0),
            'order_id'     => $this->order->id,
            'order_number' => $this->order->order_number,
            'amount'       => $this->order->total_amount,
            'customer'     => $this->order->user->name,
            'url'          => route('restaurant.dashboard'),
            'icon'         => 'bi-bag-plus',
            'color'        => 'warning',
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
