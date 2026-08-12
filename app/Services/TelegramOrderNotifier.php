<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramOrderNotifier
{
    private function escape(string|int|float|null $value): string
    {
        return htmlspecialchars(
            (string) ($value ?? ''),
            ENT_QUOTES | ENT_SUBSTITUTE,
            'UTF-8'
        );
    }

    public function notify(Order $order): void
    {
        $token = env('TELEGRAM_BOT_TOKEN');
        $chatId = env('TELEGRAM_CHAT_ID');

        if (empty($token) || empty($chatId)) {
            Log::info("Telegram notification skipped for Order {$order->id}: Missing credentials.");
            return;
        }

        $message = "🆕 <b>New Order Received!</b>\n\n";
        $message .= "<b>Order ID:</b> " . $this->escape($order->id) . "\n";
        $message .= "<b>Customer:</b> " . $this->escape($order->customer_name) . "\n";
        $message .= "<b>Phone:</b> " . $this->escape($order->phone) . "\n";
        $message .= "<b>Location:</b> " . $this->escape($order->location) . "\n";
        $message .= "<b>Delivery Date:</b> " . $this->escape($order->delivery_date->format('Y-m-d')) . "\n";
        $message .= "<b>Delivery Slot:</b> " . $this->escape($order->delivery_slot) . "\n\n";
        
        $message .= "<b>Items:</b>\n";
        foreach ($order->items as $item) {
            $message .= "- " . $this->escape($item['quantity']) . "x " . $this->escape($item['name']) . " (" . $this->escape(number_format($item['subtotal'], 2)) . ")\n";
        }
        
        $message .= "\n<b>Total:</b> " . $this->escape(number_format($order->total_amount, 2)) . " JOD\n";

        if (!empty($order->gift_note)) {
            $message .= "\n<b>Gift Note:</b> " . $this->escape($order->gift_note);
        }

        if (!empty($order->special_instructions)) {
            $message .= "\n<b>Special Instructions:</b> " . $this->escape($order->special_instructions);
        }

        try {
            $response = Http::timeout(10)->post("https://api.telegram.org/bot{$token}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $message,
                'parse_mode' => 'HTML',
            ]);

            if (!$response->successful()) {
                Log::error("Telegram API Error for Order {$order->id}: " . $response->body());
            }
        } catch (\Exception $e) {
            Log::error("Telegram exception for Order {$order->id}: " . $e->getMessage());
        }
    }
}
