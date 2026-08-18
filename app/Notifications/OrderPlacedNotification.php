<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderPlacedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(private readonly Order $order) {}

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
            ->subject("Order Confirmed — #{$this->order->order_number}")
            ->greeting("Hello {$notifiable->name}!")
            ->line("Your order has been placed successfully.")
            ->line("**Order Number:** #{$this->order->order_number}")
            ->line("**Total Amount:** ₹" . number_format($this->order->total_amount, 2))
            ->line("**Payment Method:** " . ucfirst($this->order->payment_method))
            ->line("**Estimated Delivery:** {$this->order->restaurant->delivery_time} minutes")
            ->action('Track Your Order', route('customer.orders.show', $this->order))
            ->line('Thank you for ordering from Laravel-Delivery!');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type'         => 'order_placed',
            'title'        => 'Order Placed Successfully!',
            'message'      => "Your order #{$this->order->order_number} has been placed.",
            'order_id'     => $this->order->id,
            'order_number' => $this->order->order_number,
            'amount'       => $this->order->total_amount,
            'url'          => route('customer.orders.show', $this->order),
            'icon'         => 'bi-bag-check',
            'color'        => 'success',
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
