<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class OrderReceiptService
{
    public function __construct(private OrderAccountingService $accounting) {}

    public function forCustomer(Order $order, User $user): Order
    {
        if ($order->user_id !== $user->id || $order->is_walk_in) {
            abort(404);
        }

        return $this->loadReceiptRelations($order);
    }

    public function forAdmin(Order $order): Order
    {
        return $this->loadReceiptRelations($order);
    }

    /**
     * @return array<string, mixed>
     */
    public function viewData(Order $order, string $returnUrl, string $returnLabel, string $downloadUrl): array
    {
        return [
            'downloadUrl' => $downloadUrl,
            'isPaid' => $this->accounting->countsAsPaid($order),
            'issuedAt' => now()->format('F j, Y, g:i A'),
            'logoDataUri' => $this->logoDataUri(),
            'order' => $order,
            'orderedAt' => $order->created_at?->format('F j, Y, g:i A') ?? 'Not recorded',
            'receiptNumber' => 'RCPT-'.$order->order_number,
            'returnLabel' => $returnLabel,
            'returnUrl' => $returnUrl,
            'statusLabel' => Str::of($order->status)->replace('_', ' ')->title()->toString(),
        ];
    }

    public function downloadFilename(Order $order): string
    {
        return 'loveby-ade-receipt-'.$order->order_number.'.pdf';
    }

    private function loadReceiptRelations(Order $order): Order
    {
        return $order->loadMissing('items');
    }

    private function logoDataUri(): ?string
    {
        if (! extension_loaded('gd')) {
            return null;
        }

        $path = public_path('images/lovebyadelogo.png');

        if (! File::exists($path)) {
            return null;
        }

        return 'data:'.File::mimeType($path).';base64,'.base64_encode(File::get($path));
    }
}
