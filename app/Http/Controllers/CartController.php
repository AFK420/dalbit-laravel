<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class CartController extends Controller
{
    /**
     * Display the cart page.
     */
    public function index(Request $request): Response
    {
        $cart = $request->session()->get('cart', []);
        
        if (empty($cart)) {
            return response()->view('cart.index', ['items' => [], 'total' => 0]);
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

        return response()->view('cart.index', [
            'items' => $items,
            'total' => $total,
        ]);
    }

    /**
     * Add a product to the cart.
     */
    public function add(Request $request)
    {
        $validated = $request->validate([
            'product_id' => [
                'required',
                'uuid',
                Rule::exists('products', 'id')->where(
                    fn ($query) => $query->where('is_visible', true)
                ),
            ],
            'quantity' => 'required|integer|min:1|max:100',
        ]);

        $productId = $validated['product_id'];
        $quantity = $validated['quantity'];

        $cart = $request->session()->get('cart', []);
        
        $newQuantity = ($cart[$productId] ?? 0) + $quantity;

        if ($newQuantity > 100) {
            return back()
                ->withInput()
                ->withErrors([
                    'quantity' => 'The maximum quantity for one product is 100.',
                ]);
        }

        $cart[$productId] = $newQuantity;
        
        $request->session()->put('cart', $cart);

        return redirect()->route('cart.index');
    }

    /**
     * Update cart quantity.
     */
    public function update(Request $request, Product $product)
    {
        if (! $product->is_visible) {
            return redirect()
                ->route('cart.index')
                ->withErrors(['cart' => 'That product is no longer available.']);
        }

        $validated = $request->validate([
            'quantity' => 'required|integer|min:1|max:100',
        ]);
        
        $cart = $request->session()->get('cart', []);
        
        if (isset($cart[$product->id])) {
            $cart[$product->id] = $validated['quantity'];
            $request->session()->put('cart', $cart);
        }

        return redirect()->route('cart.index');
    }

    /**
     * Remove a product from the cart.
     */
    public function destroy(Request $request, Product $product)
    {
        $cart = $request->session()->get('cart', []);
        
        if (isset($cart[$product->id])) {
            unset($cart[$product->id]);
            $request->session()->put('cart', $cart);
        }

        return redirect()->route('cart.index');
    }
}
