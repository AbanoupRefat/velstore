<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewOrderNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $order;
    public $customer;
    public $items;

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
        $this->customer = $order->customer;
        $this->items = $order->details()->with(['product.translation', 'productVariant'])->get();
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('New Order #' . $this->order->id . ' Received')
                    ->view('emails.new-order-admin')
                    ->with([
                        'orderNumber' => $this->order->id,
                        'orderDate' => $this->order->created_at->format('d M Y, h:i A'),
                        'customerName' => $this->customer->name ?? 'Guest',
                        'customerEmail' => $this->customer->email ?? 'N/A',
                        'customerPhone' => $this->customer->phone ?? 'N/A',
                        'shippingAddress' => $this->order->shipping_address,
                        'paymentMethod' => $this->order->payment_method,
                        'total' => $this->order->total_price,
                        'items' => $this->items,
                        'adminUrl' => url('/admin/orders/' . $this->order->id),
                    ]);
    }
}
