<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateOrderStatusRequest;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->string('status')->toString();
        $search = $request->string('search')->toString();
        $pageSize = (int) $request->integer('page_size', 10);
        $pageSize = in_array($pageSize, [5, 10, 20], true) ? $pageSize : 10;

        $orders = Order::query()
            ->with(['items.product', 'user'])
            ->when(in_array($status, Order::statuses(), true), function ($query) use ($status): void {
                $query->where('status', $status);
            })
            ->when($search, function ($query) use ($search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('order_number', 'like', '%'.$search.'%')
                        ->orWhere('full_name', 'like', '%'.$search.'%')
                        ->orWhere('email_address', 'like', '%'.$search.'%')
                        ->orWhereHas('items', function ($query) use ($search): void {
                            $query->where('product_title', 'like', '%'.$search.'%');
                        });
                });
            })
            ->latest()
            ->paginate($pageSize)
            ->withQueryString();

        return view('pages.admin.orders', [
            'orders' => $orders,
            'statusCounts' => Order::query()
                ->selectRaw('status, COUNT(*) as aggregate')
                ->groupBy('status')
                ->pluck('aggregate', 'status')
                ->all(),
            'statuses' => Order::statuses(),
        ]);
    }

    public function update(UpdateOrderStatusRequest $request, Order $order): RedirectResponse
    {
        $validated = $request->validated();

        $order->update([
            'status' => $validated['status'],
            'cancellation_reason' => $validated['status'] === Order::STATUS_CANCELLED
                ? $validated['cancellation_reason']
                : null,
        ]);

        return redirect()
            ->route('admin.orders')
            ->with('status', 'Order status updated.');
    }
}
