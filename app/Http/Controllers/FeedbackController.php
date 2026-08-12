<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFeedbackRequest;
use App\Models\CustomerFeedback;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FeedbackController extends Controller
{
    public function create(Request $request, Order $order)
    {
        $this->authorizeOrderSession($request, $order);

        if (
            $order->customerFeedback()->exists() ||
            $request->session()->has($this->sessionKey($order))
        ) {
            return redirect()->route('feedback.thanks');
        }

        return response()->view('feedback.index', [
            'order' => $order,
            'locale' => session('locale', config('app.locale')),
        ]);
    }

    public function store(
        StoreFeedbackRequest $request,
        Order $order
    ) {
        $this->authorizeOrderSession($request, $order);

        if (
            $order->customerFeedback()->exists() ||
            $request->session()->has($this->sessionKey($order))
        ) {
            return redirect()->route('feedback.thanks');
        }

        $validated = $request->validated();
        $rating = (int) $validated['rating'];

        $request->session()->put($this->sessionKey($order), true);

        if ($rating <= 3) {
            CustomerFeedback::create([
                'order_id' => $order->id,
                'rating' => $rating,
                'comments' => $validated['comments'] ?? null,
                'created_at' => now(),
            ]);

            return redirect()->route('feedback.thanks');
        }

        $reviewUrl = config('services.google_maps.review_url');

        if (filled($reviewUrl)) {
            return redirect()->away($reviewUrl);
        }

        Log::warning(
            "Google Maps review URL is not configured for order {$order->id}."
        );

        return redirect()->route('feedback.thanks');
    }

    public function thanks(Request $request)
    {
        return response()->view('feedback.thanks', [
            'locale' => session('locale', config('app.locale')),
        ]);
    }

    private function authorizeOrderSession(
        Request $request,
        Order $order
    ): void {
        abort_unless(
            hash_equals(
                (string) $request->session()->get('last_order_id'),
                (string) $order->id
            ),
            404
        );
    }

    private function sessionKey(Order $order): string
    {
        return 'feedback_submitted:' . $order->id;
    }
}
