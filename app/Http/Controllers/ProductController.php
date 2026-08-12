<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    /**
     * Display the public storefront: visible products grouped into
     * "packages" and "sweets", each ordered by category then name.
     *
     * Locale note: the actual AR/EN toggle UI is built in Feature 2.
     * For now we read the active locale from the session (so Feature 2
     * only has to set `session('locale')` and this keeps working), and
     * fall back to the app's default locale if nothing is set yet.
     */
    public function index(Request $request): Response
    {
        $locale = session('locale', config('app.locale'));

        $products = Product::query()
            ->where('is_visible', true)
            ->orderBy('category')
            ->orderBy('name')
            ->get();

        $packages = $products->where('type', 'package')->values();
        $sweets = $products->where('type', 'sweet')->values();

        return response()->view('storefront.index', [
            'packages' => $packages,
            'sweets' => $sweets,
            'locale' => $locale,
        ]);
    }
}
