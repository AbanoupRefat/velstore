<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderStatusUpdate extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $customer;
    public $oldStatus;
    public $newStatus;

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order, $oldStatus, $newStatus)
    {
        $this->order = $order;
        $this->customer = $order->customer;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $statusMessages = [
            'pending' => 'Your order has been received and is being processed.',
            'processing' => 'Your order is currently being prepared for shipment.',
            'shipped' => 'Your order has been shipped and is on its way!',
            'delivered' => 'Your order has been delivered. Thank you for shopping with us!',
            'cancelled' => 'Your order has been cancelled.',
        ];

        return $this->subject('Order #' . $this->order->id . ' Status Update')
                    ->view('emails.order-status-update')
                    ->with([
                        'orderNumber' => $this->order->id,
                        'customerName' => $this->customer->name ?? 'Customer',
                        'oldStatus' => ucfirst($this->oldStatus),
                        'newStatus' => ucfirst($this->newStatus),
                        'statusMessage' => $statusMessages[$this->newStatus] ?? 'Your order status has been updated.',
                        'trackingNumber' => $this->order->tracking_number,
                        'trackingUrl' => $this->order->tracking_number ? url('/track-order?number=' . $this->order->id) : null,
                    ]);
    }
}
