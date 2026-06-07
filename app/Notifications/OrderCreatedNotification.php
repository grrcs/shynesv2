<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $order;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order)
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
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $order = $this->order;
        $subtotal = $order->items->sum(function($item) {
            return $item->price * $item->quantity;
        });

        return (new MailMessage)
            ->subject('Pesanan #' . $order->invoice_number . ' Berhasil Dibuat')
            ->greeting('Halo ' . $notifiable->name . '!')
            ->line('Terima kasih telah melakukan pemesanan di Shyness OS.')
            ->line('Berikut adalah detail pesanan Anda:')
            ->line('**Nomor Invoice:** ' . $order->invoice_number)
            ->line('**Tanggal:** ' . $order->created_at->format('d M Y H:i'))
            ->line('**Status:** ' . ucfirst($order->status))
            ->line('**Total:** Rp ' . number_format($order->total_price, 0, ',', '.'))
            ->action('Lihat Detail Pesanan', url('/orders/' . $order->id))
            ->line('Kami akan segera memproses pesanan Anda.')
            ->line('Terima kasih!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'invoice_number' => $this->order->invoice_number,
            'total_price' => $this->order->total_price,
            'status' => $this->order->status,
            'message' => 'Pesanan Anda #' . $this->order->invoice_number . ' telah berhasil dibuat.',
        ];
    }
}
