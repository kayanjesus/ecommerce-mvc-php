<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NovoPedidoNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray($notifiable)
    {
        return [
            'message' => 'Novo pedido #' . $this->pedido->id_pedido . ' recebido - R$ ' . number_format($this->pedido->total, 2, ',', '.'),
            'link' => route('adm.pedidos.detalhes', $this->pedido->id_pedido),
            'pedido_id' => $this->pedido->id_pedido
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage($this->toArray($notifiable));
    }
}
