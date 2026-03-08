<?php

namespace App\Http\Controllers\Admin\Catalog;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\SubscriptionPlan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubscriptionPlanController extends Controller
{
    public function index(): View
    {
        $plans = SubscriptionPlan::with('description')->orderByDesc('subscription_plan_id')->paginate(20);
        return view('admin.catalog.subscription-plan.index', compact('plans'));
    }

    public function create(): View
    {
        $languages = Language::where('status', true)->get();
        return view('admin.catalog.subscription-plan.form', compact('languages'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'frequency'         => ['required', 'string'],
            'duration'          => ['required', 'integer', 'min:0'],
            'price'             => ['required', 'numeric', 'min:0'],
            'trial_frequency'   => ['nullable', 'string'],
            'trial_duration'    => ['nullable', 'integer'],
            'trial_price'       => ['nullable', 'numeric'],
            'trial_status'      => ['boolean'],
            'status'            => ['boolean'],
        ]);

        $plan = SubscriptionPlan::create($request->only(
            'frequency', 'duration', 'price', 'trial_frequency', 'trial_duration', 'trial_price', 'trial_status', 'sort_order', 'status'
        ));

        foreach ($request->input('descriptions', []) as $langId => $desc) {
            $plan->descriptions()->create(['language_id' => $langId, 'name' => $desc['name']]);
        }

        return redirect()->route('admin.catalog.subscription-plan.index')->with('success', 'Subscription plan added.');
    }

    public function edit(SubscriptionPlan $subscriptionPlan): View
    {
        $subscriptionPlan->load('descriptions');
        $languages = Language::where('status', true)->get();
        return view('admin.catalog.subscription-plan.form', compact('subscriptionPlan', 'languages'));
    }

    public function update(Request $request, SubscriptionPlan $subscriptionPlan): RedirectResponse
    {
        $request->validate([
            'frequency' => ['required', 'string'],
            'duration'  => ['required', 'integer', 'min:0'],
            'price'     => ['required', 'numeric', 'min:0'],
        ]);

        $subscriptionPlan->update($request->only(
            'frequency', 'duration', 'price', 'trial_frequency', 'trial_duration', 'trial_price', 'trial_status', 'sort_order', 'status'
        ));

        foreach ($request->input('descriptions', []) as $langId => $desc) {
            $subscriptionPlan->descriptions()->updateOrCreate(['language_id' => $langId], ['name' => $desc['name']]);
        }

        return redirect()->route('admin.catalog.subscription-plan.index')->with('success', 'Subscription plan updated.');
    }

    public function destroy(SubscriptionPlan $subscriptionPlan): RedirectResponse
    {
        $subscriptionPlan->delete();
        return redirect()->route('admin.catalog.subscription-plan.index')->with('success', 'Subscription plan deleted.');
    }
}
