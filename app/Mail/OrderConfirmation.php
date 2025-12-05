<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderConfirmation extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $order;
    public $customer;
    public $items;
    public $total;

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
        $this->customer = $order->customer;
        $this->items = $order->details()->with(['product.translation', 'productVariant'])->get();
        $this->total = $order->total_price;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Order Confirmation #' . $this->order->id . ' - ' . config('app.name'))
                    ->view('emails.order-confirmation')
                    ->with([
                        'orderNumber' => $this->order->id,
                        'orderDate' => $this->order->created_at->format('d M Y'),
                        'customerName' => $this->customer->name ?? 'Customer',
                        'shippingAddress' => $this->order->shipping_address,
                        'paymentMethod' => $this->order->payment_method,
                        'items' => $this->items,
                        'total' => $this->total,
                    ]);
    }
}
