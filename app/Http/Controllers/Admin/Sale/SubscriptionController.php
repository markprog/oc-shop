<?php

namespace App\Http\Controllers\Admin\Sale;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubscriptionController extends Controller
{
    public function index(Request $request): View
    {
        $query = Subscription::with('customer', 'plan.description', 'order');

        if ($request->filled('customer')) {
            $query->whereHas('customer', fn($q) => $q->where('email', 'like', '%' . $request->customer . '%'));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $subscriptions = $query->orderByDesc('date_added')->paginate(20)->withQueryString();

        return view('admin.sale.subscription.index', compact('subscriptions'));
    }

    public function show(Subscription $subscription): View
    {
        $subscription->load('customer', 'plan.description', 'order');
        return view('admin.sale.subscription.show', compact('subscription'));
    }

    public function cancel(Subscription $subscription): RedirectResponse
    {
        $subscription->update(['status' => 'cancelled', 'date_next' => null]);
        return back()->with('success', 'Subscription cancelled.');
    }
}
