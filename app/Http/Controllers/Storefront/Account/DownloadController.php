<?php

namespace App\Http\Controllers\Storefront\Account;

use App\Http\Controllers\Controller;
use App\Models\OrderProduct;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DownloadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    public function index(): View
    {
        // Fetch downloads for completed orders
        $downloads = auth('web')->user()
            ->orders()
            ->with('products.downloads.description')
            ->whereHas('status') // only completed orders
            ->get()
            ->flatMap(fn($order) => $order->products->flatMap(fn($p) => $p->downloads));

        return view('storefront.account.downloads', compact('downloads'));
    }

    public function download(int $downloadId): BinaryFileResponse|RedirectResponse
    {
        $customer = auth('web')->user();

        // Verify customer has access to this download via orders
        $hasAccess = $customer->orders()
            ->whereHas('products.downloads', fn($q) => $q->where('downloads.download_id', $downloadId))
            ->exists();

        if (!$hasAccess) {
            abort(403, 'Access denied.');
        }

        $download = \App\Models\Download::findOrFail($downloadId);

        // Decrement remaining if limited
        if ($download->remaining > 0) {
            $download->decrement('remaining');
        } elseif ($download->remaining === 0 && $download->remaining !== -1) {
            return back()->with('error', 'Download limit reached.');
        }

        $path = storage_path('app/downloads/' . $download->filename);

        if (!file_exists($path)) {
            abort(404);
        }

        return response()->download($path, $download->mask);
    }
}
