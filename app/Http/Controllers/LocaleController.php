<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class LocaleController extends Controller
{
    /**
     * Flips ar <-> en, writes it to the session (read side used by
     * Product.php's localized* accessors) and to a 1-year cookie
     * (so the choice survives across browser sessions).
     */
    public function toggle(Request $request)
    {
        $current = $request->session()->get('locale', config('app.locale'));
        $next = $current === 'ar' ? 'en' : 'ar';

        $request->session()->put('locale', $next);

        return redirect()
            ->back()
            ->withCookie(Cookie::forever('locale', $next));
    }
}
