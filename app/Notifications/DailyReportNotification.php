<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DailyReportNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(private readonly array $stats)
    {
        //
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
            ->subject("Laravel-Delivery Daily Report — {$this->stats['date']}")
            ->greeting("Hello {$notifiable->name}!")
            ->line("Here is your daily summary for **{$this->stats['date']}**:")
            ->line("---")
            ->line("📦 **Total Orders:** {$this->stats['total_orders']}")
            ->line("✅ **Delivered:** {$this->stats['delivered_orders']}")
            ->line("❌ **Cancelled:** {$this->stats['cancelled_orders']}")
            ->line("💰 **Total Revenue:** ₹" . number_format($this->stats['total_revenue'], 2))
            ->line("👤 **New Customers:** {$this->stats['new_users']}")
            ->line("🏪 **Active Restaurants:** {$this->stats['active_restaurants']}")
            ->line("---")
            ->action('View Full Report', route('admin.dashboard'))
            ->line('This is an automated daily report from Laravel-Delivery.');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type'    => 'daily_report',
            'title'   => "Daily Report — {$this->stats['date']}",
            'message' => "Orders: {$this->stats['total_orders']} | Revenue: ₹" . number_format($this->stats['total_revenue'], 0),
            'stats'   => $this->stats,
            'url'     => route('admin.dashboard'),
            'icon'    => 'bi-bar-chart-fill',
            'color'   => 'primary',
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
