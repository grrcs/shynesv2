<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderStatusUpdatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $order;
    protected $oldStatus;
    protected $newStatus;
    protected $notes;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order, string $oldStatus, string $newStatus, ?string $notes = null)
    {
        $this->order = $order;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
        $this->notes = $notes;
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
        $order = $this->order;
        $statusMessages = [
            'pending' => 'Pesanan Anda sedang menunggu konfirmasi pembayaran.',
            'confirmed' => 'Pembayaran telah dikonfirmasi. Pesanan Anda sedang diproses.',
            'processing' => 'Pesanan Anda sedang dipersiapkan untuk dikirim.',
            'shipped' => 'Pesanan Anda telah dikirim!',
            'delivered' => 'Pesanan Anda telah sampai!',
            'cancelled' => 'Pesanan Anda telah dibatalkan.',
        ];

        $message = (new MailMessage)
            ->subject('Update Status Pesanan #' . $order->invoice_number)
            ->greeting('Halo ' . $notifiable->name . '!')
            ->line('Status pesanan Anda telah diperbarui.')
            ->line('**Nomor Invoice:** ' . $order->invoice_number)
            ->line('**Status Sebelumnya:** ' . ucfirst(str_replace('_', ' ', $this->oldStatus)))
            ->line('**Status Saat Ini:** ' . ucfirst(str_replace('_', ' ', $this->newStatus)));

        if (isset($statusMessages[$this->newStatus])) {
            $message->line($statusMessages[$this->newStatus]);
        }

        if ($this->notes) {
            $message->line('**Catatan:** ' . $this->notes);
        }

        $message->action('Lihat Detail Pesanan', url('/orders/' . $order->id . '/track'))
                ->line('Terima kasih telah berbelanja di Shyness OS!');

        return $message;
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
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'notes' => $this->notes,
            'message' => 'Status pesanan #' . $this->order->invoice_number . ' berubah dari ' . ucfirst($this->oldStatus) . ' ke ' . ucfirst($this->newStatus),
        ];
    }
}
