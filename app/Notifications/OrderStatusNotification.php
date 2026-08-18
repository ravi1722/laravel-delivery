<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        private readonly Order $order,
        private readonly string $previousStatus
    ) {
        //
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
     * Get the mail representation of the notification.
     */
    // public function toMail(object $notifiable): MailMessage
    // {
    //     return (new MailMessage)
    //         ->line('The introduction to the notification.')
    //         ->action('Notification Action', url('/'))
    //         ->line('Thank you for using our application!');
    // }

    public function toDatabase(object $notifiable): array
    {
        $messages = [
            'confirmed' => "Your order #{$this->order->order_number} is confirmed!",
            'preparing' => "Restaurant is preparing your order.",
            'ready'     => "Your order is ready for pickup!",
            'picked_up' => "Your order is on the way! 🛵",
            'delivered' => "Order delivered! Enjoy your meal! 🎉",
            'cancelled' => "Order #{$this->order->order_number} has been cancelled.",
        ];

        $icons = config('constants.notify_icons');
        $colors = config('constants.status_colors');
        return [
            'type'         => 'order_status',
            'title'        => 'Order Update',
            'message'      => $messages[$this->order->status] ?? 'Order status updated.',
            'order_id'     => $this->order->id,
            'order_number' => $this->order->order_number,
            'status'       => $this->order->status,
            'url'          => route('customer.orders.show', $this->order),
            'icon'         => $icons[$this->order->status] ?? 'bi-bell',
            'color'        => $colors[$this->order->status] ?? 'secondary',
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
