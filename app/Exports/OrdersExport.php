<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Illuminate\Support\Collection;

class OrdersExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithEvents
{
    protected $status;
    protected $dateFrom;
    protected $dateTo;

    public function __construct($status = null, $dateFrom = null, $dateTo = null)
    {
        $this->status = $status;
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        $query = Order::with(['customer', 'details.product.translation', 'details.productVariant.translation', 'details.productVariant.attributeValues.attribute'])
            ->latest();

        // Apply status filter
        if ($this->status && $this->status !== 'all') {
            $query->where('status', $this->status);
        }

        // Apply date range filter
        if ($this->dateFrom) {
            $query->whereDate('created_at', '>=', $this->dateFrom);
        }
        if ($this->dateTo) {
            $query->whereDate('created_at', '<=', $this->dateTo);
        }

        $orders = $query->get();

        // Transform orders into rows (one row per order item)
        $rows = new Collection();

        foreach ($orders as $order) {
            $customerName = $order->customer ? $order->customer->name : ($order->guest_email ?? 'Guest');
            $customerEmail = $order->customer ? $order->customer->email : ($order->guest_email ?? 'N/A');
            $customerPhone = $order->customer ? ($order->customer->phone ?? 'N/A') : 'N/A';

            if ($order->details && $order->details->count() > 0) {
                foreach ($order->details as $detail) {
                    $productName = $detail->product && $detail->product->translation 
                        ? $detail->product->translation->name 
                        : 'N/A';
                    
                    $productSKU = $detail->product ? ($detail->product->sku ?? 'N/A') : 'N/A';
                    
                    // Get variant name and attributes
                    $variantName = 'N/A';
                    $attributes = 'N/A';
                    
                    if ($detail->productVariant) {
                        // Get variant name from translation
                        $variantName = $detail->productVariant->translation->name ?? 'Default';
                        
                        // Get all attribute values with their attribute names
                        $attrList = [];
                        if ($detail->productVariant->attributeValues && $detail->productVariant->attributeValues->count() > 0) {
                            foreach ($detail->productVariant->attributeValues as $attrValue) {
                                $attrName = $attrValue->attribute->name ?? 'Attribute';
                                $attrVal = $attrValue->value ?? 'N/A';
                                $attrList[] = "{$attrName}: {$attrVal}";
                            }
                        }
                        $attributes = !empty($attrList) ? implode(', ', $attrList) : 'N/A';
                    }

                    $rows->push([
                        $order->id,
                        $customerName,
                        $customerEmail,
                        $customerPhone,
                        $order->created_at->format('Y-m-d H:i:s'),
                        ucfirst($order->status),
                        ucfirst($order->payment_method ?? 'N/A'),
                        ucfirst($order->payment_status ?? 'N/A'),
                        ucfirst($order->shipping_method ?? 'N/A'),
                        $order->tracking_number ?? 'N/A',
                        number_format($order->shipping_cost ?? 0, 2),
                        number_format($order->total_price - ($order->shipping_cost ?? 0), 2),
                        number_format($order->total_price, 2),
                        $order->shipping_address ?? 'N/A',
                        $order->billing_address ?? 'N/A',
                        $productName,
                        $productSKU,
                        $variantName,
                        $attributes,
                        $detail->quantity,
                        number_format($detail->unit_price, 2),
                        number_format($detail->quantity * $detail->unit_price, 2),
                        number_format($order->discount_amount ?? 0, 2),
                        $order->coupon_code ?? 'N/A'
                    ]);
                }
            } else {
                // If no order details, still show the order
                $rows->push([
                    $order->id,
                    $customerName,
                    $customerEmail,
                    $customerPhone,
                    $order->created_at->format('Y-m-d H:i:s'),
                    ucfirst($order->status),
                    ucfirst($order->payment_method ?? 'N/A'),
                    ucfirst($order->payment_status ?? 'N/A'),
                    ucfirst($order->shipping_method ?? 'N/A'),
                    $order->tracking_number ?? 'N/A',
                    number_format($order->shipping_cost ?? 0, 2),
                    number_format($order->total_price - ($order->shipping_cost ?? 0), 2),
                    number_format($order->total_price, 2),
                    $order->shipping_address ?? 'N/A',
                    $order->billing_address ?? 'N/A',
                    'N/A',
                    'N/A',
                    'N/A',
                    'N/A',
                    'N/A',
                    'N/A',
                    'N/A',
                    number_format($order->discount_amount ?? 0, 2),
                    $order->coupon_code ?? 'N/A'
                ]);
            }
        }

        return $rows;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Order ID',
            'Customer Name',
            'Customer Email',
            'Customer Phone',
            'Order Date',
            'Status',
            'Payment Method',
            'Payment Status',
            'Shipping Method',
            'Tracking Number',
            'Shipping Cost',
            'Subtotal',
            'Total Amount',
            'Shipping Address',
            'Billing Address',
            'Product Name',
            'Product SKU',
            'Variant',
            'Attributes (Size, Color, etc.)',
            'Quantity',
            'Unit Price',
            'Item Total',
            'Discount',
            'Coupon Code'
        ];
    }

    /**
     * Apply styles to the worksheet
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the header row
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                    'size' => 11,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4472C4'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }

    /**
     * Register events for additional styling
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Get the highest row and column
                $highestRow = $event->sheet->getHighestRow();
                $highestColumn = $event->sheet->getHighestColumn();

                // Apply borders to all cells
                $event->sheet->getStyle("A1:{$highestColumn}{$highestRow}")
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN)
                    ->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('CCCCCC'));

                // Set row height for header
                $event->sheet->getRowDimension(1)->setRowHeight(25);

                // Wrap text for address columns
                $event->sheet->getStyle("N2:O{$highestRow}")
                    ->getAlignment()
                    ->setWrapText(true);

                // Center align specific columns
                $centerColumns = ['A', 'E', 'F', 'G', 'H', 'I', 'J', 'S'];
                foreach ($centerColumns as $col) {
                    $event->sheet->getStyle("{$col}2:{$col}{$highestRow}")
                        ->getAlignment()
                        ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                }

                // Right align number columns
                $numberColumns = ['K', 'L', 'M', 'T', 'U', 'V'];
                foreach ($numberColumns as $col) {
                    $event->sheet->getStyle("{$col}2:{$col}{$highestRow}")
                        ->getAlignment()
                        ->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                }

                // Add alternating row colors
                for ($i = 2; $i <= $highestRow; $i++) {
                    if ($i % 2 == 0) {
                        $event->sheet->getStyle("A{$i}:{$highestColumn}{$i}")
                            ->getFill()
                            ->setFillType(Fill::FILL_SOLID)
                            ->setStartColor(new \PhpOffice\PhpSpreadsheet\Style\Color('F8F9FA'));
                    }
                }

                // Freeze the header row
                $event->sheet->freezePane('A2');
            },
        ];
    }
}
