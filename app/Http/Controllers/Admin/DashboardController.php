<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(): View
    {
        $orders = Order::query()
            ->with('handledByAdmin')
            ->orderBy('delivery_date')
            ->orderBy('delivery_slot')
            ->get()
            ->groupBy(fn (Order $order) => $order->status->value);

        return view('admin.dashboard', [
            'orders' => $orders,
            'statuses' => OrderStatus::cases(),
            'admin' => Auth::guard('admin')->user(),
        ]);
    }

    public function updateStatus(
        Request $request,
        Order $order
    ): RedirectResponse {
        $validated = $request->validate([
            'status' => [
                'required',
                'string',
                'in:pending_confirmation,new,in_progress,completed,cancelled',
            ],
        ]);

        DB::transaction(function () use ($validated, $order): void {
            $order->status = OrderStatus::from($validated['status']);
            $order->handled_by_admin_id = Auth::guard('admin')->id();
            $order->handled_at = now('Asia/Amman');
            $order->save();
        });

        return back()->with('status', 'Order status updated.');
    }
}
