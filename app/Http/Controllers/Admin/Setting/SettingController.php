<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function general(): View
    {
        $settings = $this->loadSettings(['config_name', 'config_owner', 'config_address', 'config_email', 'config_telephone', 'config_meta_title', 'config_meta_description']);
        return view('admin.setting.general', compact('settings'));
    }

    public function saveGeneral(Request $request): RedirectResponse
    {
        $request->validate([
            'config_name'  => ['required', 'string', 'max:64'],
            'config_email' => ['required', 'email'],
        ]);

        $this->saveSettings($request->except('_token', '_method'));

        return back()->with('success', 'General settings saved.');
    }

    public function store(): View
    {
        $settings = $this->loadSettings(['config_url', 'config_ssl', 'config_maintenance', 'config_logo', 'config_icon']);
        return view('admin.setting.store', compact('settings'));
    }

    public function saveStore(Request $request): RedirectResponse
    {
        $request->validate([
            'config_url' => ['required', 'url'],
        ]);

        $this->saveSettings($request->except('_token', '_method'));

        return back()->with('success', 'Store settings saved.');
    }

    public function localisation(): View
    {
        $settings = $this->loadSettings([
            'config_country_id', 'config_zone_id', 'config_language', 'config_currency',
            'config_timezone', 'config_date_format_short', 'config_date_format_long',
            'config_time_format', 'config_weight_class_id', 'config_length_class_id',
        ]);
        return view('admin.setting.localisation', compact('settings'));
    }

    public function saveLocalisation(Request $request): RedirectResponse
    {
        $this->saveSettings($request->except('_token', '_method'));
        return back()->with('success', 'Localisation settings saved.');
    }

    public function option(): View
    {
        $settings = $this->loadSettings([
            'config_customer_group_id', 'config_customer_price', 'config_login_attempts',
            'config_tax', 'config_tax_customer', 'config_cart_weight', 'config_checkout_guest',
            'config_order_status_id', 'config_processing_status_id', 'config_complete_status_id',
            'config_review_status', 'config_review_guest', 'config_product_limit',
        ]);
        return view('admin.setting.option', compact('settings'));
    }

    public function saveOption(Request $request): RedirectResponse
    {
        $this->saveSettings($request->except('_token', '_method'));
        return back()->with('success', 'Option settings saved.');
    }

    protected function loadSettings(array $keys): array
    {
        $settings = [];
        foreach ($keys as $key) {
            $settings[$key] = Setting::get($key);
        }
        return $settings;
    }

    protected function saveSettings(array $data, int $storeId = 0): void
    {
        foreach ($data as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key, 'store_id' => $storeId],
                ['value' => is_array($value) ? json_encode($value) : $value, 'serialized' => is_array($value)]
            );
        }
    }
}
