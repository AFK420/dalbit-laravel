<?php

namespace App\Http\Controllers;

use App\Models\QrScan;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class QrScanController extends Controller
{
    private const ALLOWED_SOURCES = [
        'box',
        'instagram',
        'card',
        'flyer',
    ];

    public function redirect(Request $request)
    {
        $requestedSource = $request->query('source', 'box');

        $source = is_string($requestedSource)
            ? strtolower(trim($requestedSource))
            : 'box';

        if (! in_array($source, self::ALLOWED_SOURCES, true)) {
            $source = 'box';
        }

        QrScan::create([
            'source' => $source,
            'scanned_at' => Carbon::now('Asia/Amman'),
        ]);

        $configuredUrl = config('services.qr.box_url');

        if (
            is_string($configuredUrl) &&
            filter_var($configuredUrl, FILTER_VALIDATE_URL) &&
            in_array(parse_url($configuredUrl, PHP_URL_SCHEME), ['http', 'https'], true)
        ) {
            return redirect()->away($configuredUrl);
        }

        return redirect()->route('storefront.index');
    }
}
