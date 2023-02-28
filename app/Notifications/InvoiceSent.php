<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Invoice;
use Illuminate\Support\HtmlString;

;

class InvoiceSent extends Notification
{
    use Queueable;

    private $invoice;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($invoice)
    {
        $this->invoice = $invoice;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            // ->line(new HtmlString($header))
            ->line(new HtmlString('<p style="text-align:left; font-size:16px; color: #808080">' . $this->invoice['name'] . '</p><br>'))
            ->line(new HtmlString('<p style="text-align:justify; font-size:14px; color: #808080">' . $this->invoice['salutation'] . '</p>'))
            ->attach(public_path() . '/authorizedPDF/' . $this->invoice['fileName'] . '.pdf' )
            ->attach(public_path() . '/authorized/' . $this->invoice['fileName'] . '.xml' )
            ->line(new HtmlString('<p style="text-align:justify; font-size:14px; color: #808080">' . $this->invoice['content'] . '</p><br>'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
