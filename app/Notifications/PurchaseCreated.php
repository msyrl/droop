<?php

namespace App\Notifications;

use App\Models\SalesOrder;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

class PurchaseCreated extends Notification
{
    use Queueable;

    /** @var SalesOrder */
    private $salesOrder;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(SalesOrder $salesOrder)
    {
        $this->salesOrder = $salesOrder;
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
     * @param  mixed|null  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable = null)
    {
        $pdf = Pdf::loadView('my-purchase.invoice.index', [
            'purchase' => $this->salesOrder,
        ]);

        return (new MailMessage)
            ->attachData($pdf->output(), "invoice-{$this->salesOrder->name}.pdf", [
                'mime' => 'application/pdf',
            ])
            ->subject(Lang::get('Purchase :name Successfully Created', [
                'name' => $this->salesOrder->name,
            ]))
            ->greeting(Lang::get('Hello :name,', [
                'name' => $this->salesOrder->user->name,
            ]))
            ->line(Lang::get('You just created purchase to our platform. Please check attachment below to get the details.'))
            ->line(Lang::get('Thank you for using our platform!'));
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
