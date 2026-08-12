<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;
use App\Models\Product;
use App\Services\TelegramOrderNotifier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Carbon\Carbon;

class CheckoutController extends Controller
{
    /**
     * Display the checkout form.
     */
    public function index(Request $request)
    {
        $cart = $request->session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('storefront.index');
        }

        $products = Product::query()
            ->where('is_visible', true)
            ->whereIn('id', array_keys($cart))
            ->get()
            ->keyBy('id');

        $items = [];
        $total = 0;

        foreach ($cart as $productId => $quantity) {
            if ($product = $products->get($productId)) {
                $subtotal = $product->price * $quantity;
                $total += $subtotal;
                $items[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'subtotal' => $subtotal,
                ];
            }
        }

        if (empty($items)) {
            $request->session()->forget('cart');
            return redirect()->route('storefront.index');
        }

        // Calculate earliest delivery date based on current time in Asia/Amman
        $now = Carbon::now('Asia/Amman');
        
        if ($now->hour < 21) {
            $earliestDate = $now->copy()->addDay()->format('Y-m-d');
        } else {
            $earliestDate = $now->copy()->addDays(2)->format('Y-m-d');
        }

        return response()->view('checkout.index', [
            'items' => $items,
            'total' => $total,
            'earliestDate' => $earliestDate,
            'locale' => session('locale', config('app.locale')),
        ]);
    }

    /**
     * Process checkout.
     */
    public function store(StoreOrderRequest $request, TelegramOrderNotifier $telegramNotifier)
    {
        // 1. Check rate limits: 3 orders per IP per day (Asia/Amman)
        $ip = $request->ip();
        $date = Carbon::now('Asia/Amman')->format('Y-m-d');
        $rateLimitKey = 'checkout:' . $ip . ':' . $date;

        if (RateLimiter::tooManyAttempts($rateLimitKey, 3)) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['checkout' => 'You have reached the maximum number of orders for today.']);
        }

        // 2. Load cart & products securely
        $cart = $request->session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('storefront.index');
        }

        $products = Product::query()
            ->where('is_visible', true)
            ->whereIn('id', array_keys($cart))
            ->get()
            ->keyBy('id');
        
        $items = [];
        $totalAmount = 0;
        
        foreach ($cart as $productId => $quantity) {
            if ($product = $products->get($productId)) {
                $subtotal = $product->price * $quantity;
                $totalAmount += $subtotal;
                
                $items[] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'quantity' => $quantity,
                    'price' => (float) $product->price,
                    'subtotal' => (float) $subtotal,
                ];
            }
        }

        if (empty($items)) {
            $request->session()->forget('cart');
            return redirect()->route('storefront.index');
        }

        // 3. Create the order
        $validated = $request->validated();
        
        $order = Order::create([
            'customer_name' => $validated['customer_name'],
            'phone' => $validated['phone'],
            'location' => $validated['location'],
            'gift_note' => $validated['gift_note'] ?? null,
            'special_instructions' => $validated['special_instructions'] ?? null,
            'items' => $items,
            'total_amount' => $totalAmount,
            'status' => 'pending_confirmation',
            'ip_address' => $ip,
            'delivery_date' => $validated['delivery_date'],
            'delivery_slot' => $validated['delivery_slot'],
        ]);

        // 4. Hit rate limiter
        RateLimiter::hit($rateLimitKey, 86400); // 24 hours decay

        // 5. Send Telegram Notification
        try {
            $telegramNotifier->notify($order);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Telegram notification failed for Order ' . $order->id . ': ' . $e->getMessage());
        }

        // 6. Clear cart, store last_order_id for success authorization, & redirect
        $request->session()->forget('cart');
        $request->session()->put('last_order_id', $order->id);
        
        return redirect()->route('checkout.success', $order);
    }

    /**
     * Checkout success page.
     */
    public function success(Request $request, Order $order)
    {
        abort_unless(
            hash_equals(
                (string) $request->session()->get('last_order_id'),
                (string) $order->id
            ),
            404
        );

        return response()->view('checkout.success', [
            'order' => $order,
            'locale' => session('locale', config('app.locale')),
        ]);
    }
}
